<?php

namespace App\Http\Controllers;

use App\Custom\Lightspeed\Subscriber;
use App\Models\LightspeedAuth;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class LightspeedSubscriberController extends Controller
{
    public function import() {
        $lightspeedAuth = LightspeedAuth::where("user_id", Auth::user()->id)->first();
        if (empty($lightspeedAuth)) {
            return redirect('/wizard')->withWarning("Please set API key and secret in settings");
        }
        try {
            $subscribers = (new Subscriber)->get();
        } catch (\Exception $e) {
            return redirect('/')->withWarning($e->getMessage());
        }

        $newSubscribers = array_map(function($x) { 
            $x['isConfirmedCustomer'] = $x['isConfirmed'];
            $x['languageCode'] = $x['language']['code'] ?? null;
            $x['languageTitle'] = $x['language']['title'] ?? null;
            unset($x['isConfirmed']); 
            unset($x['language']); 
            unset($x['doNotifyConfirmed']); 
            $x['lightspeed_id'] = $x['id'];
            $x['user_id'] = Auth::user()->id;
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
            return redirect('/')->withWarning($e->getMessage());
            }
        }

        return redirect('/lightspeed/subscribers')->withSuccess("Subscribers imported.");
    }

    public function importapi() {
        set_time_limit(180);
        $lightspeedAuth = LightspeedAuth::first();
        if (empty($lightspeedAuth)) {
            return response()->json( ['success'=>false, 'message' =>"Please set API key and secret in settings"], 401 );
        }
        try {
            $subscribers = (new \App\Custom\Lightspeed\Subscriber)->get();
            $orders = (new \App\Custom\Lightspeed\Order)->get();
            $checkouts = (new \App\Custom\Lightspeed\Checkout)->get();
        } catch (\Exception $e) {
            return response()->json( ['success'=>false, 'message' =>$e->getMessage()], 401 );
        }

        $newSubscribers = array_map(function($x) { 
            $x['isConfirmedCustomer'] = $x['isConfirmed'];
            $x['languageCode'] = $x['language']['code'] ?? null;
            $x['languageTitle'] = $x['language']['title'] ?? null;
            unset($x['isConfirmed']); 
            unset($x['language']); 
            unset($x['doNotifyConfirmed']); 
            $x['lightspeed_id'] = $x['id'];
            $x['user_id'] = Auth::user()->id;
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
                return response()->json( ['success'=>false, 'message' =>$e->getMessage()], 401 );
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
                        'user_id' => Auth::user()->id,
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
                        'user_id' => \Auth::user()->id,
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
                        'user_id'  => \Auth::user()->id,
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
                return response()->json( ['success'=>false, 'message' =>$e->getMessage()], 401 );
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
                        'user_id' => Auth::user()->id,
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
                return response()->json( ['success'=>false, 'message' =>$e->getMessage()], 401 );
            }
        }

        try {
            (new CopernicaController)->profileCreate();
        } catch (\Exception $e) {
            return response()->json( ['success'=>false, 'message' =>$e->getMessage()], 401 );
        }
        return response()->json( ['success'=>true, 'message' =>"Subscribers imported."], 200 );
    }


    public function show()
    {
        $lightspeedAuth = LightspeedAuth::where("user_id", Auth::user()->id)->first();
        if (empty($lightspeedAuth)) {
            return redirect('/wizard')->withWarning("Please set API key and secret in settings");
        }
        // try {
        //     $subscribers = (new Subscriber)->get();
        // } catch (\Exception $e) {
        //     return redirect('/')->withWarning($e->getMessage());
        // }
        $customers = \App\Models\OrderPerson::where('user_id', \Auth::user()->id)->get();
        $orderPersonEmails = \App\Models\OrderPerson::pluck('email')->all();
        $subscribers = \App\Models\Subscriber::whereNotIn('email', $orderPersonEmails)->where('user_id', \Auth::user()->id)->get();

        return view('admin.lightspeed.subscriber.index', compact('subscribers', 'customers'));
    }
}
