<?php

namespace App\Http\Controllers;

use App\Custom\Lightspeed\Order;
use App\Custom\Lightspeed\Customer;
use App\Custom\Lightspeed\Product;
use App\Models\LightspeedAuth;
use Illuminate\Support\Facades\Auth;

class LightspeedOrderController extends Controller
{
    
    public function import() {
        $lightspeedAuth = LightspeedAuth::where("user_id", Auth::user()->id)->first();
        if (empty($lightspeedAuth)) {
            return redirect('/')->withWarning("Please set API key and secret in settings");
        }
        try {
            $orders = (new Order)->get();
        } catch (\Exception $e) {
            return redirect('/')->withWarning($e->getMessage());
        }
        // dd($orders);


        foreach ($orders as $order) {
            try {
                $createdAt = $order['createdAt'] ? new \DateTime($order['createdAt']) : null;
                $updatedAt = $order['updatedAt'] ? new \DateTime($order['updatedAt']) : null;
                $orderObj = \App\Models\Order::updateOrCreate(
                    [
                        'lightspeed_id'     =>  $order['id']],
                    [
                        'user_id' => Auth::user()->id,
                        'lightspeed_id' => $order['id'],
                        'number' => $order['number'],
                        'createdAt' => $createdAt ? $createdAt->format('Y-m-d H:i:s') : null,
                        'updatedAt' => $updatedAt ? $updatedAt->format('Y-m-d H:i:s') : null,
                        'status' => $order['status'],
                        'priceIncl' => $order['priceIncl'],
                        'email' => $order['email'],
                        'deliveryDate' => $order['deliveryDate'],
                    ]
                );
                $customer = (new Customer)->getById($order['customer']['resource']['id']);
                $customerCreatedAt = $customer['createdAt'] ? new \DateTime($customer['createdAt']) : null;
                $customerUpdatedAt = $customer['updatedAt'] ? new \DateTime($customer['updatedAt']) : null;
                $lastOnlineAt = $customer['lastOnlineAt'] ? new \DateTime($customer['lastOnlineAt']) : null;

                $orderPerson = \App\Models\OrderPerson::updateOrCreate(
                    [                        
                        'user_id' => Auth::user()->id,
                        'order_id' => $orderObj->id,
                        'person_id' => $customer['id']
                    ],
                    [
                        'user_id' => Auth::user()->id,
                        'order_id' => $orderObj->id,
                        'person_id' => $customer['id'],
                        'nationalId' => $order['nationalId'],
                        'email' => $order['email'],
                        'gender' => $order['gender'],
                        'firstName' => $order['firstname'],
                        'lastName' => $order['lastname'],
                        'phone' => $order['phone'],
                        'mobile' => $order['mobile'],
                        'remoteIp' => $order['remoteIp'],
                        'birthDate' => $order['birthDate'],
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

                $products = (new Order)->orderProducts($order['id']);
                foreach ($products as $product) {
                    $variant = (new Order)->variant($product['variant']['resource']['id']);
                    $orderProduct = \App\Models\OrderProduct::updateOrCreate(
                    [                        
                        'user_id' => Auth::user()->id,
                        'order_id' => $orderObj->id,
                        'product_id' => $product['id']],
                    [
                        'user_id'  => Auth::user()->id,
                        'order_id' => $orderObj->id,
                        'product_id' => $product['id'],
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
                return redirect('/')->withWarning($e->getMessage());
            }
        }

        return redirect('/lightspeed/orders')->withSuccess("Orders imported.");
    }

    public function show()
    {
        $lightspeedAuth = LightspeedAuth::where("user_id", Auth::user()->id)->first();
        if (empty($lightspeedAuth)) {
            return redirect('/')->withWarning("Please set API key and secret in settings");
        }
        try {
            $orders = (new Order)->get();
        } catch (\Exception $e) {
            return redirect('/')->withWarning($e->getMessage());
        }
        
        return view('admin.lightspeed.order.index', compact('orders'));
    }

}
