<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\LightspeedAuth;
use Illuminate\Support\Facades\Auth;

class sync_lightspeed extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lightspeed:sync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'sync from lightspeed to copernica';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {       
        $lightspeedAuth = LightspeedAuth::first();
        if (empty($lightspeedAuth)) {
            $this->info('Please set API key and secret in settings');
            return 0;
        }
        
        try {
            $subscribers = (new \App\Custom\Lightspeed\Subscriber)->get();
            $orders = (new \App\Custom\Lightspeed\Order)->get();
            $checkouts = (new \App\Custom\Lightspeed\Checkout)->get();
        } catch (\Exception $e) {
            $this->info($e->getMessage());
            return 0;
        }

        $copernica_auths = \App\CopernicaAuth::whereNotNull('api_secret')->whereNotNull('api_secret')->whereNotNull('token')->get();
            foreach($copernica_auths as $copernica_auth) :
            $newSubscribers = array_map(function($x) { 
                $x['isConfirmedCustomer'] = $x['isConfirmed'];
                $x['languageCode'] = $x['language']['code'] ?? null;
                $x['languageTitle'] = $x['language']['title'] ?? null;
                unset($x['isConfirmed']); 
                unset($x['language']); 
                unset($x['doNotifyConfirmed']); 
                $x['lightspeed_id'] = $x['id'];
                $x['user_id'] = $copernica_auth->user_id;
                unset($x['id']);
                $createdAt = new \DateTime($x['createdAt']);
                $updatedAt = new \DateTime($x['updatedAt']);
                $x['createdAt'] = $createdAt->format('Y-m-d H:i:s');
                $x['updatedAt'] = $updatedAt->format('Y-m-d H:i:s');
                return $x; 
            }, $subscribers);

            foreach ($newSubscribers as $subscriber) {
                try {
                    $profile = \App\Models\Subscriber::updateOrCreate(
                        ['email'     =>  $subscriber['email']],
                        $subscriber
                    );
                } catch (\Exception $e) {
                    $this->info($e->getMessage());
                    return 0;
                }
            }

            
            foreach ($orders as $order) {
                try {
                    $createdAt = $order['createdAt'] ? new \DateTime($order['createdAt']) : null;
                    $updatedAt = $order['updatedAt'] ? new \DateTime($order['updatedAt']) : null;
                    $customer = (new \App\Custom\Lightspeed\Customer)->getById($order['customer']['resource']['id']);
                    $customerCreatedAt = $customer['createdAt'] ? new \DateTime($customer['createdAt']) : null;
                    $customerUpdatedAt = $customer['updatedAt'] ? new \DateTime($customer['updatedAt']) : null;
                    $birthDate = $customer['birthDate'] ? new \DateTime($customer['birthDate']) : null;
                    
                    $lastOnlineAt = $customer['lastOnlineAt'] ? new \DateTime($customer['lastOnlineAt']) : null;

                    $orderPerson = \App\Models\OrderPerson::updateOrCreate(
                        [                        
                            
                            'email' => $order['email'],
                        ],
                        [
                            'user_id' => $copernica_auth->user_id,
                            'customerId' => $customer['id'],
                            'nationalId' => $customer['nationalId'],
                            'email' => $customer['email'],
                            'gender' => $customer['gender'],
                            'firstName' => $customer['firstname'],
                            'lastName' => $customer['lastname'],
                            'phone' => $customer['phone'],
                            'mobile' => $customer['mobile'],
                            'remoteIp' => $customer['remoteIp'],
                            'birthDate' => $birthDate ? $birthDate->format('Y-m-d H:i:s') : null,
                            'isCompany' => $customer['isCompany'],
                            'companyName' => $customer['companyName'],
                            'companyCoCNumber' => $customer['companyCoCNumber'],
                            'companyVatNumber' => $customer['companyVatNumber'],
                            'addressBillingName' => $customer['addressBillingName'],
                            'addressBillingStreet' => $customer['addressBillingStreet'],
                            'addressBillingStreet2' => $customer['addressBillingStreet2'],
                            'addressBillingNumber' => $customer['addressBillingNumber'],
                            'addressBillingExtension' => $customer['addressBillingExtension'],
                            'addressBillingZipcode' => $customer['addressBillingZipcode'],
                            'addressBillingCity' => $customer['addressBillingCity'],
                            'addressBillingRegion' => $customer['addressBillingRegion'],
                            'addressBillingCountryCode' => $customer['addressBillingCountry']['code'],
                            'addressBillingCountryTitle' => $customer['addressBillingCountry']['title'],
                            'addressShippingName' => $customer['addressShippingName'],
                            'addressShippingStreet' => $customer['addressShippingStreet'],
                            'addressShippingStreet2' => $customer['addressShippingStreet2'],
                            'addressShippingNumber' => $customer['addressShippingNumber'],
                            'addressShippingExtension' => $customer['addressShippingExtension'],
                            'addressShippingZipcode' => $customer['addressShippingZipcode'],
                            'addressShippingCity' => $customer['addressShippingCity'],
                            'addressShippingRegion' => $customer['addressShippingRegion'],
                            'addressShippingCountryCode' => $customer['addressShippingCountry']['code'],
                            'addressShippingCountryTitle' => $customer['addressShippingCountry']['title'],
                            'languageCode' => $customer['language']['code'],
                            'languageTitle' => $customer['language']['title'],
                            'languageLocale' => $customer['language']['locale'],
                            'isConfirmedCustomer' => $customer['isConfirmed'],
                            'customerCreatedAt' => $customerCreatedAt ? $customerCreatedAt->format('Y-m-d H:i:s') : null,
                            'customerUpdatedAt' => $customerUpdatedAt ? $customerUpdatedAt->format('Y-m-d H:i:s') : null,
                            'lastOnlineAt' => $lastOnlineAt ? $lastOnlineAt->format('Y-m-d H:i:s') : null,
                            'customerType' => $customer['type'],
                            'optInNewsletter' => $order['newsletterSubscribed'],
                            'nieuwsbrief' => $order['newsletterSubscribed'] ? 'Ja' : 'Nee',
                        ]
                    );

                    $orderObj = \App\Models\Order::updateOrCreate(
                        [
                            'orderId'     =>  $order['id']],
                        [
                            'user_id' => $copernica_auth->user_id,
                            'orderId' => $order['id'],
                            'customerId' => $customer['id'],
                            'orderNumber' => $order['number'],
                            'createdAt' => $createdAt ? $createdAt->format('Y-m-d H:i:s') : null,
                            'updatedAt' => $updatedAt ? $updatedAt->format('Y-m-d H:i:s') : null,
                            'status' => $order['status'],
                            'priceIncl' => $order['priceIncl'],
                            'email' => $order['email'],
                            'deliveryDate' => $order['deliveryDate'],
                        ]
                    );

                    $products = (new \App\Custom\Lightspeed\Order)->orderProducts($order['id']);
                    foreach ($products as $product) {
                        $variant = (new \App\Custom\Lightspeed\Order)->variant($product['variant']['resource']['id']);
                        $orderProduct = \App\Models\OrderProduct::updateOrCreate(
                        [                        
                            'orderId' => $order['id'],
                            'productId' => $product['id'],
                        ],
                        [
                            'user_id'  => $copernica_auth->user_id,
                            'orderRowID' => $product['id'],
                            'orderId' => $order['id'],
                            'productId' => $product['id'],
                            'productTitle' => $product['productTitle'],
                            'varientId' => $product['variant']['resource']['id'],
                            'varientTitle' => $variant['title'],
                            'quantityOrdered' => $product['quantityOrdered'],
                            'quantityReturned' => $product['quantityReturned'],
                            'basePriceIncl' => $product['basePriceIncl'],
                            'priceIncl' => $product['priceIncl'],
                            'email' => $order['email'],
                        ]);
                    }
                } catch (\Exception $e) {
                    $this->info($e->getMessage());
                    return 0;
                }
            }

            foreach ($checkouts as $checkout) {
                if(!empty($checkout['payment_method'])) {
                    continue;
                }
                try {
                    $createdAt = $checkout['created_at'] ? new \DateTime($checkout['created_at']) : null;
                    $updatedAt = $checkout['updated_at'] ? new \DateTime($checkout['updated_at']) : null;
                    $customer = $checkout['customer'];
                    $billing_address = $checkout['billing_address'];
                    $shipping_address = $checkout['shipping_address'];
                    $birthDate = $customer['birthdate'] ? new \DateTime($customer['birthdate']) : null;
                    

                    $orderPerson = \App\Models\Checkout::updateOrCreate(
                        [                        
                            'checkoutId' => $checkout['id'],
                        ],
                        [
                            'user_id' => $copernica_auth->user_id,
                            'checkoutId' => $checkout['id'],
                            'nationalId' => $customer['national_id'],
                            'email' => $customer['email'],
                            'gender' => $customer['gender'],
                            'firstName' => $customer['firstname'],
                            'lastName' => $customer['lastname'],
                            'phone' => $customer['phone'],
                            'mobile' => $customer['mobile'],
                            'birthDate' => $birthDate ? $birthDate->format('Y-m-d H:i:s') : null,
                            'company' => $customer['company'],
                            'coCNumber' => $customer['cocnumber'],
                            'vatNumber' => $customer['vatnumber'],
                            'addressBillingName' => $billing_address['name'],
                            'addressBillingStreet' => $billing_address['address1'],
                            'addressBillingStreet2' => $billing_address['address2'],
                            'addressBillingNumber' => $billing_address['number'],
                            'addressBillingExtension' => $billing_address['extension'],
                            'addressBillingZipcode' => $billing_address['zipcode'],
                            'addressBillingCity' => $billing_address['city'],
                            'addressBillingRegion' => $billing_address['region'],
                            'addressBillingCountryCode' => $billing_address['country'],
                            'addressShippingName' => $shipping_address['name'],
                            'addressShippingStreet' => $shipping_address['address1'],
                            'addressShippingStreet2' => $shipping_address['address2'],
                            'addressShippingNumber' => $shipping_address['number'],
                            'addressShippingExtension' => $shipping_address['extension'],
                            'addressShippingZipcode' => $shipping_address['zipcode'],
                            'addressShippingCity' => $shipping_address['city'],
                            'addressShippingRegion' => $shipping_address['region'],
                            'addressShippingCountryCode' => $shipping_address['country'],
                            'createdAt' => $createdAt ? $createdAt->format('Y-m-d H:i:s') : null,
                            'updatedAt' => $updatedAt ? $updatedAt->format('Y-m-d H:i:s') : null,
                            'customerType' => $customer['type'],
                            'optInNewsletter' => $checkout['newsletter'] ? true : false,
                            'nieuwsbrief' => $checkout['newsletter'] ? 'Ja' : 'Nee',
                        ]
                    );
                } catch (\Exception $e) {
                    $this->info($e->getMessage());
                    return 0;
                }
            }

            try {
                (new CopernicaController)->profileCreate();
            } catch (\Exception $e) {
                $this->info($e->getMessage());
                return 0;
            }
        endforeach;
        $this->info("Sync completed.");
        return 0;
    
    }
}
