<?php
 
namespace App\Http\Controllers;
 
use Illuminate\Http\Request;
use Validator,Redirect,Response;
Use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Session;
use App\Models\CopernicaAuth;
use App\Models\LightspeedAuth;
use App\Custom\Copernica\Profile;
use App\Custom\Copernica\Copernica;
 
class AuthController extends Controller
{

    function __construct()
    {
    }
    
    public function success()
    {
        return view('success');
    }

    public function index()
    {
        return view('admin.auth.login');
    }  
 
    public function registration()
    {
        return view('admin.auth.register');
    }
     
    public function postLogin(Request $request)
    {
        request()->validate([
            'email' => 'required',
            'password' => 'required',
        ]);
 
        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {
            $copernicaAuth = CopernicaAuth::where("user_id", Auth::user()->id)->first();
            $lightspeedAuth = LightspeedAuth::where("user_id", Auth::user()->id)->first();

            if (empty($lightspeedAuth) || empty($lightspeedAuth->api_key) || empty($copernicaAuth) || empty($copernicaAuth->token)) {
                return redirect('/wizard');
            } else {
                try {
                    $subscribers = (new \App\Custom\Lightspeed\Subscriber)->get();
                    $orders = (new \App\Custom\Lightspeed\Order)->get();
                } catch (\Exception $e) {
                    return redirect('/wizard')->withWarning($e->getMessage());
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
                        return redirect('/wizard')->withWarning($e->getMessage());
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
                        return redirect('/wizard')->withWarning($e->getMessage());
                    }
                }

                $profile = new Profile();

                $orderPersonEmails = \App\Models\OrderPerson::pluck('email')->all();
        $subscribers = \App\Models\Subscriber::whereNotIn('email', $orderPersonEmails)->where('user_id', \Auth::user()->id)->select('id','profile_id','firstname','lastname','email', 'createdAt', 'updatedAt', 'isConfirmedCustomer', 'languageCode', 'languageTitle', 'optInNewsletter', 'nieuwsbrief')->get();
        if($subscribers->first()) {
            foreach ($subscribers as $subscriber) {
                try {
                    $profileData = $subscriber->toArray();
                    unset($profileData['id']);
                    unset($profileData['profile_id']);
                    if (!empty($subscriber->profile_id)) {
                        $parameters = array(
                            'fields'    =>  array("email=={$subscriber->email}"),
                            'async'     =>  1,
                            'create'    =>  0
                        );
                        $profile->update($profileData, Copernica::USER_DATABASE_NAME, $parameters);
                        $profileID = $subscriber->profile_id;
                    } else {
                        $profileID = $profile->create($profileData, Copernica::USER_DATABASE_NAME, true);
                        $subscriber->isSaved = true;
                        $subscriber->profile_id = $profileID;
                        $subscriber->save();
                    }
                } catch (\Exception $e) {
                    return response()->json( ['success'=>false, 'message' =>$e->getMessage()], 401 );
                }
            }
        }

        $databases = (new Copernica)->getAllDatabases();

        $id = (new Copernica)->getDatabaseId($databases['data'], Copernica::USER_DATABASE_NAME);
        $collections = (new Copernica)->getAllCollections($id);
        $orderCollectionID = (new Copernica)->getcollectionId($collections['data'], Copernica::ORDER_COLLECTION_NAME);
        $productCollectionID = (new Copernica)->getcollectionId($collections['data'], Copernica::ORDER_ROW_COLLECTION_NAME);

        $profile = new Profile();

        $subscribers = \App\Models\OrderPerson::where('user_id', \Auth::user()->id)->select('id', 'profile_id', 'customerId', 'nationalId', 'email', 'gender', 'firstName', 'lastName', 'phone', 'mobile', 'remoteIp', 'birthDate', 'isCompany', 'companyName', 'companyCoCNumber', 'companyVatNumber', 'addressBillingName', 'addressBillingStreet', 'addressBillingStreet2', 'addressBillingNumber', 'addressBillingExtension', 'addressBillingZipcode', 'addressBillingCity', 'addressBillingRegion', 'addressBillingCountryCode', 'addressBillingCountryTitle', 'addressShippingName', 'addressShippingStreet', 'addressShippingStreet2', 'addressShippingNumber', 'addressShippingExtension', 'addressShippingZipcode', 'addressShippingCity', 'addressShippingRegion', 'addressShippingCountryCode', 'addressShippingCountryTitle', 'languageCode', 'languageTitle', 'isConfirmedCustomer', 'customerCreatedAt', 'customerUpdatedAt', 'lastOnlineAt', 'languageLocale', 'customerType', 'optInNewsletter', 'nieuwsbrief')->get();
        if ($subscribers->first()) {

            foreach ($subscribers as $subscriber) {
                try {
                    $subscriberData = $subscriber->toArray();
                    $customerId = $subscriberData['customerId'];
                    unset($subscriberData['id']);
                    unset($subscriberData['customerId']);
                    unset($subscriberData['profile_id']);
                    if (!empty($subscriber->profile_id)) {
                        $parameters = array(
                            'fields'    =>  array("email=={$subscriber->email}"),
                            'async'     =>  1,
                            'create'    =>  0
                        );
                        $profile->update($subscriberData, Copernica::USER_DATABASE_NAME, $parameters);
                        $profileID = $subscriber->profile_id;
                    } else {
                        $profileID = $profile->create($subscriberData, $id, true);
                        $subscriber->isSaved = true;
                        $subscriber->profile_id = $profileID;
                        $subscriber->save();
                    }
                    $orders = \App\Models\Order::where('customerId', $customerId)->whereNull('isSaved')->select('id', 'orderId', 'orderNumber', 'createdAt', 'updatedAt', 'status', 'priceIncl', 'email', 'deliveryDate')->get();
                    foreach($orders as $order ) {
                        $orderData = $order->toArray();
                        unset($orderData['id']);
                        $orderRes = $profile->createSubprofile($profileID, $orderCollectionID, $orderData, true);
                        $order->isSaved = true;
                        $order->save();
                        $products = \App\Models\OrderProduct::where('orderId', $order->orderId)->whereNull('isSaved')->select('id', 'productId', 'productTitle', 'varientId', 'varientTitle', 'quantityOrdered', 'quantityReturned', 'basePriceIncl', 'priceIncl', 'email')->get();
                        foreach($products as $product ) {
                            $productData = $product->toArray();
                            unset($productData['id']);
                            $productRes = $profile->createSubprofile($profileID, $productCollectionID, $productData, true);
                            $order->isSaved = true;
                            $order->save(); 
                        }
                    }
                } catch (\Exception $e) {
                    return response()->json( ['success'=>false, 'message' =>$e->getMessage()], 401 );
                }
            }
        }

            }
            return redirect()->intended('dashboard');
        }

        return Redirect::to("login")->withSuccess('Opps! You have entered invalid credentials');
    }

    public function postRegistration(Request $request)
    {
        request()->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
        ]);
         
        $data = $request->all();
 
        try {
            $check = $this->create($data);
        } catch (\Exception $e) {
            return Redirect::to("registration")->withSuccess('Something went wrong.');
        }
        
       
        return Redirect::to("login")->withSuccess('You have Successfully registered. Now, login using email and password.');
    }
     
    public function dashboard()
    {
        $copernicaAuth = CopernicaAuth::where("user_id", Auth::user()->id)->first();
        $lightspeedAuth = LightspeedAuth::where("user_id", Auth::user()->id)->first();

        if (empty($lightspeedAuth) || empty($lightspeedAuth->api_key) || empty($copernicaAuth) || empty($copernicaAuth->token)) {
            return redirect('/wizard');
        }
        return view('admin.dashboard');
    }
 
    public function create(array $data)
    {
      return User::create([
        'name' => $data['name'],
        'email' => $data['email'],
        'password' => Hash::make($data['password'])
      ]);
    }
     
    public function logout() {
        Session::flush();
        Auth::logout();
        return Redirect('login');
    }
     
    public function copernica()
    {
        return view('admin.copernica');
    }
}