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
        $lightspeedAuth = LightspeedAuth::where("user_id", Auth::user()->id)->first();
        if (empty($lightspeedAuth)) {
            return response()->json( ['success'=>false, 'message' =>"Please set API key and secret in settings"], 401 );
        }
        try {
            $subscribers = (new \App\Custom\Lightspeed\Subscriber)->get();
            $orders = (new \App\Custom\Lightspeed\Order)->get();
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
                        'nationalId' => $order['nationalId'],
                        'email' => $order['email'],
                        'gender' => $order['gender'],
                        'firstName' => $order['firstname'],
                        'lastName' => $order['lastname'],
                        'phone' => $order['phone'],
                        'mobile' => $order['mobile'],
                        'remoteIp' => $order['remoteIp'],
                        'birthDate' => $birthDate ? $birthDate->format('Y-m-d H:i:s') : null,
                        'isCompany' => $order['isCompany'],
                        'companyName' => $order['companyName'],
                        'companyCoCNumber' => $order['companyCoCNumber'],
                        'companyVatNumber' => $order['companyVatNumber'],
                        'addressBillingName' => $order['addressBillingName'],
                        'addressBillingStreet' => $order['addressBillingStreet'],
                        'addressBillingStreet2' => $order['addressBillingStreet2'],
                        'addressBillingNumber' => $order['addressBillingNumber'],
                        'addressBillingExtension' => $order['addressBillingExtension'],
                        'addressBillingZipcode' => $order['addressBillingZipcode'],
                        'addressBillingCity' => $order['addressBillingCity'],
                        'addressBillingRegion' => $order['addressBillingRegion'],
                        'addressBillingCountryCode' => $order['addressBillingCountry']['code'],
                        'addressBillingCountryTitle' => $order['addressBillingCountry']['title'],
                        'addressShippingName' => $order['addressShippingName'],
                        'addressShippingStreet' => $order['addressShippingStreet'],
                        'addressShippingStreet2' => $order['addressShippingStreet2'],
                        'addressShippingNumber' => $order['addressShippingNumber'],
                        'addressShippingExtension' => $order['addressShippingExtension'],
                        'addressShippingZipcode' => $order['addressShippingZipcode'],
                        'addressShippingCity' => $order['addressShippingCity'],
                        'addressShippingRegion' => $order['addressShippingRegion'],
                        'addressShippingCountryCode' => $order['addressShippingCountry']['code'],
                        'addressShippingCountryTitle' => $order['addressShippingCountry']['title'],
                        'languageCode' => $order['language']['code'],
                        'languageTitle' => $order['language']['title'],
                        'languageLocale' => $order['language']['locale'],
                        'isConfirmedCustomer' => $customer['isConfirmed'],
                        'customerCreatedAt' => $customerCreatedAt ? $customerCreatedAt->format('Y-m-d H:i:s') : null,
                        'customerUpdatedAt' => $customerUpdatedAt ? $customerUpdatedAt->format('Y-m-d H:i:s') : null,
                        'lastOnlineAt' => $lastOnlineAt ? $lastOnlineAt->format('Y-m-d H:i:s') : null,
                        'customerType' => $customer['type'],
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
                        'orderRowID' => $product['id']],
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
