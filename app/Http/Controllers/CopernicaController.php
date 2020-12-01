<?php

namespace App\Http\Controllers;

use App\Custom\Copernica\CopernicaRestAPI;
use App\Custom\Copernica\Database;
use App\Custom\Copernica\Profile;
use App\Custom\Lightspeed\Subscriber;
use App\Models\CopernicaAuth;
use App\Models\LightspeedAuth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Custom\Copernica\Copernica;

class CopernicaController extends Controller
{
    public function index()
    {
        $copernicaAuths = CopernicaAuth::all();
        return view('admin.copernica.index', ['copernicaAuths' => $copernicaAuths]);
    }
    public function delete($id)
    {
        try {
            $user = CopernicaAuth::find($id);
            $user->delete();
            return back()->withSuccess('Copernica token is deleted.');
        }  catch (\Exception $e) {
            return back()->withWarning($e->getMessage());
        }
    }
    public function setup()
    {
        return view('admin.copernica.setup');
    }

    public function sync()
    {
        $copernicaAuth = CopernicaAuth::where("user_id", Auth::user()->id)->first();
        if (empty($copernicaAuth->token)) {
            return redirect('/wizard')->withWarning("Copernica token not found");
        }
        return view('admin.copernica.sync');
    }

    public function identify()
    {
        $copernicaAuth = CopernicaAuth::where("user_id", Auth::user()->id)->firstOrfail();
        $CopernicaRestAPI = new CopernicaRestAPI('afafa');
        print_r($CopernicaRestAPI->get("identity"));
    }

    function getAllDatabases () {
        $copernicaAuth = CopernicaAuth::where("user_id", Auth::user()->id)->firstOrfail();
        if (empty($copernicaAuth->token)) {
            return redirect('/')->withWarning("Copernica token not found");
        }
        $database = new Database;
        $res =  $database->getAll();
        if ($res && array_key_exists('error', $res)) {
            return redirect('/')->withWarning($res['error']['message']);
        }
        return $res;
    }

    function subscriberDatabaseCreate() {
        try {
            $this->databaseCreate(Copernica::SUBSCRIBER_DATABASE_NAME);
        } catch (\Exception $e) {
            return redirect('/copernica/setup')->withWarning($e->getMessage());
        }
        return redirect('/copernica/setup')->withSuccess("Subscriber database created");
    }

    function orderDatabaseCreate() {
        try {
            // $this->databaseCreate(Copernica::ORDER_DATABASE_NAME);
            $this->collectionCreate(Copernica::ORDER_DATABASE_NAME);
        } catch (\Exception $e) {
            return redirect('/copernica/setup')->withWarning($e->getMessage());
        }
        return redirect('/copernica/setup')->withSuccess("Order database created");
    }
    function orderCollectionCreateApi() {
        try {
            $copernicaAuth = CopernicaAuth::where("user_id", Auth::user()->id)->first();
            if (empty($copernicaAuth) || empty($copernicaAuth->token)) {
                return response()->json(['success'=> false, "message" => "Copernica token not found"], 401);
            }

            $databases = (new Copernica)->getAllDatabases();
            $databaseID = (new Copernica)->getDatabaseId($databases['data'], Copernica::USER_DATABASE_NAME);
            $database = new Database;
            $database->createCollection($databaseID, Copernica::ORDER_COLLECTION_NAME);
            // $this->collectionCreate(Copernica::ORDER_COLLECTION_NAME);
            $orderDatas = 
            [
                [
                    'name' => 'customerId',
                    'type' => 'integer',
                    'displayed' => true
                ],
                [
                    'name' => 'orderId',
                    'type' => 'integer',
                    'displayed' => true
                ],
                [
                    'name' => 'orderNumber',
                    'type' => 'text',
                    'displayed' => true
                ],
                [
                    'name' => 'createdAt',
                    'type' => 'datetime',
                    'displayed' => true
                ],
                [
                    'name' => 'updatedAt',
                    'type' => 'datetime',
                    'displayed' => true
                ],
                [
                    'name' => 'status',
                    'type' => 'text',
                    'displayed' => true
                ],
                [
                    'name' => 'priceIncl',
                    'type' => 'integer',
                    'displayed' => true
                ],
                [
                    'name' => 'email',
                    'type' => 'email',
                    'displayed' => true
                ],
                [
                    'name' => 'deliveryDate',
                    'type' => 'datetime',
                    'displayed' => true
                ],
                [
                    'name' => 'pickupDate',
                    'type' => 'datetime',
                    'displayed' => true
                ],
            ];

            $collections = (new Copernica)->getAllCollections($databaseID);

            $collectionID = (new Copernica)->getcollectionId($collections['data'], Copernica::ORDER_COLLECTION_NAME);

            foreach ($orderDatas as $data) {
                $status = $database->createCollectionField($data, $collectionID);
            }
        } catch (\Exception $e) {
            return response()->json(['success'=> false, "message" => $e->getMessage()], 401);
        }
        return response()->json(['success'=> true, "message" => "Order collection created"], 200);
    }
    
    function orderRowCollectionCreateApi() {
        try {
            $copernicaAuth = CopernicaAuth::where("user_id", Auth::user()->id)->first();
            if (empty($copernicaAuth) || empty($copernicaAuth->token)) {
                return response()->json(['success'=> false, "message" => "Copernica token not found"], 401);
            }

            $databases = (new Copernica)->getAllDatabases();
            $databaseID = (new Copernica)->getDatabaseId($databases['data'], Copernica::USER_DATABASE_NAME);
            $database = new Database;
            $database->createCollection($databaseID, Copernica::ORDER_ROW_COLLECTION_NAME);
            // $this->collectionCreate(Copernica::ORDER_COLLECTION_NAME);
            $orderProductDatas = [
                [
                    'name' => 'orderRowId',
                    'type' => 'integer'
                ],
                [
                    'name' => 'orderId',
                    'type' => 'integer'
                ],
                [
                    'name' => 'productId',
                    'type' => 'text',
                    'displayed' => true
                ],
                [
                    'name' => 'productTitle',
                    'type' => 'text',
                    'displayed' => true
                ],
                [
                    'name' => 'varientId',
                    'type' => 'integer',
                    'displayed' => true
                ],
                [
                    'name' => 'varientTitle',
                    'type' => 'text',
                    'displayed' => true
                ],
                [
                    'name' => 'quantityOrdered',
                    'type' => 'integer',
                    'displayed' => true
                ],
                [
                    'name' => 'quantityReturned',
                    'type' => 'integer',
                    'displayed' => true
                ],
                [
                    'name' => 'basePriceIncl',
                    'type' => 'float',
                    'displayed' => true
                ],
                [
                    'name' => 'priceIncl',
                    'type' => 'float',
                    'displayed' => true
                ],
                [
                    'name' => 'email',
                    'type' => 'email',
                    'displayed' => true
                ],

            ];


            $collections = (new Copernica)->getAllCollections($databaseID);

            $collectionID = (new Copernica)->getcollectionId($collections['data'], Copernica::ORDER_ROW_COLLECTION_NAME);

            foreach ($orderProductDatas as $data) {
                $status = $database->createCollectionField($data, $collectionID);
            }
        } catch (\Exception $e) {
            return response()->json(['success'=> false, "message" => $e->getMessage()], 401);
        }
        return response()->json(['success'=> true, "message" => "Order row collection created"], 200);
    }

    function personCollectionCreate() {
        try {
            $this->collectionCreate(Copernica::ORDER_PERSON_COLLECTION_NAME);
        } catch (\Exception $e) {
            return redirect('/copernica/setup')->withWarning($e->getMessage());
        }
        return redirect('/copernica/setup')->withSuccess("Person collection created");
    }
    function productCollectionCreate() {
        try {
            $this->collectionCreate(Copernica::ORDER_PRODUCT_COLLECTION_NAME);
        } catch (\Exception $e) {
            return redirect('/copernica/setup')->withWarning($e->getMessage());
        }
        return redirect('/copernica/setup')->withSuccess("Product collection created");
    }

    function databaseCreate ($databse_name = "") {
        $copernicaAuth = CopernicaAuth::where("user_id", Auth::user()->id)->first();
        if (empty($copernicaAuth) || empty($copernicaAuth->token)) {
            return redirect('/copernica/setup')->withWarning("Copernica token not found");
        }
        $database = new Database;
        $status = $database->createDatabase($databse_name);
    
    }

    function collectionCreate ($collection_name) {
      $copernicaAuth = CopernicaAuth::where("user_id", Auth::user()->id)->first();
        if (empty($copernicaAuth) || empty($copernicaAuth->token)) {
            throw new Exception("Copernica token not found");
        }
        $databases = (new Copernica)->getAllDatabases();
        $databaseID = (new Copernica)->getDatabaseId($databases['data'], Copernica::USER_DATABASE_NAME);
        $database = new Database;
        return $status = $database->createCollection($databaseID, $collection_name);
    }

    function orderDatabaseFieldsCreate () {
         $copernicaAuth = CopernicaAuth::where("user_id", Auth::user()->id)->first();
        if (empty($copernicaAuth) || empty($copernicaAuth->token)) {
            return redirect('/copernica/setup')->withWarning("Copernica token not found");
        }
        $orderDatas = [
            [
                'name' => 'lightspeed_id',
                'type' => 'integer'
            ],
            [
                'name' => 'number',
                'type' => 'text'
            ],
            [
                'name' => 'createdAt',
                'type' => 'datetime'
            ],
            [
                'name' => 'updatedAt',
                'type' => 'datetime'
            ],
            [
                'name' => 'status',
                'type' => 'text'
            ],
            [
                'name' => 'priceIncl',
                'type' => 'integer'
            ],
            [
                'name' => 'email',
                'type' => 'email'
            ],
            [
                'name' => 'deliveryDate',
                'type' => 'datetime'
            ],
        ];
        $database = new Database;
        foreach ($orderDatas as $data) {
            try {
                $status = $database->createField($data, Copernica::ORDER_DATABASE_NAME);
            } catch (\Exception $e) {
                return redirect('/copernica/setup')->withWarning($e->getMessage());
            }
        }
        
        return redirect('/copernica/setup')->withSuccess("Order fields created");
    }

    function userDatabaseFieldsCreateApi () {
        set_time_limit(120);
        $copernicaAuth = CopernicaAuth::where("user_id", Auth::user()->id)->first();
        if (empty($copernicaAuth) || empty($copernicaAuth->token)) {
            return response()->json(['success'=> false, "message" => "Copernica token not found"], 401);
        }
        
        $orderProfileDatas = [
            [
                'name' => 'createdAt',
                'type' => 'datetime'
            ],
            [
                'name' => 'updatedAt',
                'type' => 'datetime'
            ],
            [
                'name' => 'nationalId',
                'type' => 'text'
            ],
            [
                'name' => 'email',
                'type' => 'email',
                'displayed' => true
            ],
            [
                'name' => 'gender',
                'type' => 'text',
                'displayed' => true
            ],
            [
                'name' => 'firstName',
                'type' => 'text',
                'displayed' => true
            ],
            [
                'name' => 'lastName',
                'type' => 'text',
                'displayed' => true
            ],
            [
                'name' => 'phone',
                'type' => 'text'
            ],
            [
                'name' => 'mobile',
                'type' => 'text'
            ],
            [
                'name' => 'remoteIp',
                'type' => 'text'
            ],
            [
                'name' => 'birthDate',
                'type' => 'empty_date'
            ],
            [
                'name' => 'isCompany',
                'type' => 'integer',
                'displayed' => true
            ],
            [
                'name' => 'companyName',
                'type' => 'text'
            ],
            [
                'name' => 'companyCoCNumber',
                'type' => 'text'
            ],
            [
                'name' => 'companyVatNumber',
                'type' => 'text'
            ],
            [
                'name' => 'addressBillingName',
                'type' => 'text'
            ],
            [
                'name' => 'addressBillingStreet',
                'type' => 'text'
            ],
            [
                'name' => 'addressBillingStreet2',
                'type' => 'text'
            ],
            [
                'name' => 'addressBillingNumber',
                'type' => 'text'
            ],
            [
                'name' => 'addressBillingExtension',
                'type' => 'text'
            ],
            [
                'name' => 'addressBillingZipcode',
                'type' => 'text'
            ],
            [
                'name' => 'addressBillingCity',
                'type' => 'text'
            ],
            [
                'name' => 'addressBillingRegion',
                'type' => 'text'
            ],
            [
                'name' => 'addressBillingCountryCode',
                'type' => 'text'
            ],
            [
                'name' => 'addressBillingCountryTitle',
                'type' => 'text'
            ],
            [
                'name' => 'addressShippingName',
                'type' => 'text'
            ],
            [
                'name' => 'addressShippingStreet',
                'type' => 'text'
            ],
            [
                'name' => 'addressShippingStreet2',
                'type' => 'text'
            ],
            [
                'name' => 'addressShippingNumber',
                'type' => 'text'
            ],
            [
                'name' => 'addressShippingExtension',
                'type' => 'text'
            ],
            [
                'name' => 'addressShippingZipcode',
                'type' => 'text'
            ],
            [
                'name' => 'addressShippingCity',
                'type' => 'text'
            ],
            [
                'name' => 'addressShippingRegion',
                'type' => 'text'
            ],
            [
                'name' => 'addressShippingCountryCode',
                'type' => 'text'
            ],
            [
                'name' => 'addressShippingCountryTitle',
                'type' => 'text'
            ],
            [
                'name' => 'languageCode',
                'type' => 'text'
            ],
            [
                'name' => 'languageTitle',
                'type' => 'text'
            ],
            [
                'name' => 'isConfirmedCustomer',
                'type' => 'integer',
                'displayed' => true
            ],
            [
                'name' => 'customerCreatedAt',
                'type' => 'empty_datetime',
                'displayed' => true
            ],
            [
                'name' => 'customerType',
                'type' => 'text'
            ],
            [
                'name' => 'optInNewsletter',
                'type' => 'integer',
                'displayed' => true
            ],
            [
                'name' => 'nieuwsbrief',
                'type' => 'text',
                'displayed' => true
            ],
            [
                'name' => 'customerUpdatedAt',
                'type' => 'empty_datetime'
            ],
            [
                'name' => 'lastOnlineAt',
                'type' => 'empty_datetime'
            ],
            [
                'name' => 'languageLocale',
                'type' => 'text'
            ]
        ];

        $database = new Database;
        // $res = $database->updateDatabase($orderProfileDatas, Copernica::USER_DATABASE_NAME);
        // dd($res);
        foreach ($orderProfileDatas as $data) {
            try {
                $status = $database->createField($data, Copernica::USER_DATABASE_NAME);
            } catch (\Exception $e) {
            
            }
        }
        
        // return response()->json(['success'=> true, "message" => "subscriber fields created"], 200);
    }

    function subscriberDatabaseFieldsCreate () {
        $copernicaAuth = CopernicaAuth::where("user_id", Auth::user()->id)->first();
        if (empty($copernicaAuth) || empty($copernicaAuth->token)) {
            return redirect('/copernica/setup')->withWarning("Copernica token not found");
        }
        $subscriberDatas = [
            [
                'name' => 'createdAt',
                'type' => 'datetime'
            ],
            [
                'name' => 'updatedAt',
                'type' => 'datetime'
            ],
            [
                'name' => 'isConfirmed',
                'type' => 'integer'
            ],
            [
                'name' => 'firstname',
                'type' => 'text'
            ],
            [
                'name' => 'lastname',
                'type' => 'text'
            ],
            [
                'name' => 'email',
                'type' => 'email'
            ]
        ];
        $database = new Database;
        foreach ($subscriberDatas as $data) {
            try {
                $status = $database->createField($data, Copernica::SUBSCRIBER_DATABASE_NAME);
            } catch (\Exception $e) {
                return redirect('/copernica/setup')->withWarning($e->getMessage());
            }
        }
        
        return redirect('/copernica/setup')->withSuccess("subscriber fields created");
    }

    function personCollectionFieldsCreate () {
        $copernicaAuth = CopernicaAuth::where("user_id", Auth::user()->id)->first();
        if (empty($copernicaAuth) || empty($copernicaAuth->token)) {
            return redirect('/copernica/setup')->withWarning("Copernica token not found");
        }
        $orderProfileDatas = [
            [
                'name' => 'order_id',
                'type' => 'integer'
            ],
            [
                'name' => 'nationalId',
                'type' => 'text'
            ],
            [
                'name' => 'email',
                'type' => 'email'
            ],
            [
                'name' => 'gender',
                'type' => 'text'
            ],
            [
                'name' => 'firstName',
                'type' => 'text'
            ],
            [
                'name' => 'lastName',
                'type' => 'text'
            ],
            [
                'name' => 'phone',
                'type' => 'text'
            ],
            [
                'name' => 'mobile',
                'type' => 'text'
            ],
            [
                'name' => 'remoteIp',
                'type' => 'text'
            ],
            [
                'name' => 'birthDate',
                'type' => 'empty_date'
            ],
            [
                'name' => 'isCompany',
                'type' => 'integer'
            ],
            [
                'name' => 'companyName',
                'type' => 'text'
            ],
            [
                'name' => 'companyCoCNumber',
                'type' => 'text'
            ],
            [
                'name' => 'companyVatNumber',
                'type' => 'text'
            ],
            [
                'name' => 'addressBillingName',
                'type' => 'text'
            ],
            [
                'name' => 'addressBillingStreet',
                'type' => 'text'
            ],
            [
                'name' => 'addressBillingStreet2',
                'type' => 'text'
            ],
            [
                'name' => 'addressBillingNumber',
                'type' => 'text'
            ],
            [
                'name' => 'addressBillingExtension',
                'type' => 'text'
            ],
            [
                'name' => 'addressBillingZipcode',
                'type' => 'text'
            ],
            [
                'name' => 'addressBillingCity',
                'type' => 'text'
            ],
            [
                'name' => 'addressBillingRegion',
                'type' => 'text'
            ],
            [
                'name' => 'addressBillingCountryCode',
                'type' => 'text'
            ],
            [
                'name' => 'addressBillingCountryTitle',
                'type' => 'text'
            ],
            [
                'name' => 'addressShippingName',
                'type' => 'text'
            ],
            [
                'name' => 'addressShippingStreet',
                'type' => 'text'
            ],
            [
                'name' => 'addressShippingStreet2',
                'type' => 'text'
            ],
            [
                'name' => 'addressShippingNumber',
                'type' => 'text'
            ],
            [
                'name' => 'addressShippingExtension',
                'type' => 'text'
            ],
            [
                'name' => 'addressShippingZipcode',
                'type' => 'text'
            ],
            [
                'name' => 'addressShippingCity',
                'type' => 'text'
            ],
            [
                'name' => 'addressShippingRegion',
                'type' => 'text'
            ],
            [
                'name' => 'addressShippingCountryCode',
                'type' => 'text'
            ],
            [
                'name' => 'addressShippingCountryTitle',
                'type' => 'text'
            ],
            [
                'name' => 'languageCode',
                'type' => 'text'
            ],
            [
                'name' => 'languageTitle',
                'type' => 'text'
            ],
            [
                'name' => 'isConfirmedCustomer',
                'type' => 'integer'
            ],
            [
                'name' => 'customerCreatedAt',
                'type' => 'empty_datetime'
            ],
            [
                'name' => 'customerUpdatedAt',
                'type' => 'empty_datetime'
            ],
            [
                'name' => 'lastOnlineAt',
                'type' => 'empty_datetime'
            ],
            [
                'name' => 'languageLocale',
                'type' => 'text'
            ],
            [
                'name' => 'customerType',
                'type' => 'text'
            ],
        ];

        $database = new Database;
        $databases = (new Copernica)->getAllDatabases();

        $id = (new Copernica)->getDatabaseId($databases['data'], Copernica::ORDER_DATABASE_NAME);

        $collections = (new Copernica)->getAllCollections($id);

        $collectionID = (new Copernica)->getcollectionId($collections['data'], Copernica::ORDER_PERSON_COLLECTION_NAME);

        foreach ($orderProfileDatas as $data) {
            try {
                $status = $database->createCollectionField($data, $collectionID);
            } catch (\Exception $e) {
                return redirect('/copernica/setup')->withWarning($e->getMessage());
            }
        }
        return redirect('/copernica/setup')->withSuccess("Person collection fields created");
    }

    function productCollectionFieldsCreate () {
        $copernicaAuth = CopernicaAuth::where("user_id", Auth::user()->id)->first();
        if (empty($copernicaAuth) || empty($copernicaAuth->token)) {
            return redirect('/copernica/setup')->withWarning("Copernica token not found");
        }
        $orderProductDatas = [
            [
                'name' => 'product_id',
                'type' => 'integer'
            ],
            [
                'name' => 'productTitle',
                'type' => 'text'
            ],
            [
                'name' => 'varientId',
                'type' => 'integer'
            ],
            [
                'name' => 'varientTitle',
                'type' => 'text'
            ],
            [
                'name' => 'quantityOrdered',
                'type' => 'integer'
            ],
            [
                'name' => 'quantityReturned',
                'type' => 'integer'
            ],
            [
                'name' => 'basePriceIncl',
                'type' => 'float'
            ],
            [
                'name' => 'priceIncl',
                'type' => 'float'
            ],
            [
                'name' => 'email',
                'type' => 'email'
            ],

        ];

        $database = new Database;
        $databases = (new Copernica)->getAllDatabases();

        $id = (new Copernica)->getDatabaseId($databases['data'], Copernica::ORDER_DATABASE_NAME);

        $collections = (new Copernica)->getAllCollections($id);

        $collectionID = (new Copernica)->getcollectionId($collections['data'], Copernica::ORDER_PRODUCT_COLLECTION_NAME);

        foreach ($orderProductDatas as $data) {
            try {
                $status = $database->createCollectionField($data, $collectionID);
            } catch (\Exception $e) {
                return redirect('/copernica/setup')->withWarning($e->getMessage());
            }
        }
        return redirect('/copernica/setup')->withSuccess("Product collection fields created");
    }

    function databaseFieldsCreate () {
        $copernicaAuth = CopernicaAuth::where("user_id", Auth::user()->id)->first();
        if (empty($copernicaAuth) || empty($copernicaAuth->token)) {
            return redirect('/copernica/setup')->withWarning("Copernica token not found");
        }
        $subscriberDatas = [
            [
                'name' => 'createdAt',
                'type' => 'datetime'
            ],
            [
                'name' => 'updatedAt',
                'type' => 'datetime'
            ],
            [
                'name' => 'isConfirmed',
                'type' => 'integer'
            ],
            [
                'name' => 'firstname',
                'type' => 'text'
            ],
            [
                'name' => 'lastname',
                'type' => 'text'
            ],
            [
                'name' => 'email',
                'type' => 'email'
            ]
        ];
        $database = new Database;
        foreach ($subscriberDatas as $data) {
            try {
                $status = $database->createField($data, Copernica::SUBSCRIBER_DATABASE_NAME);
            } catch (\Exception $e) {
                //return redirect('/copernica/setup')->withWarning($e->getMessage());
            }
        }
        
        $orderDatas = [
            [
                'name' => 'lightspeed_id',
                'type' => 'integer'
            ],
            [
                'name' => 'number',
                'type' => 'integer'
            ],
            [
                'name' => 'createdAt',
                'type' => 'datetime'
            ],
            [
                'name' => 'updatedAt',
                'type' => 'datetime'
            ],
            [
                'name' => 'status',
                'type' => 'text'
            ],
            [
                'name' => 'priceIncl',
                'type' => 'integer'
            ],
            [
                'name' => 'email',
                'type' => 'email'
            ],
            [
                'name' => 'deliveryDate',
                'type' => 'datetime'
            ],
        ];
        foreach ($orderDatas as $data) {
            try {
                $status = $database->createField($data, Copernica::ORDER_DATABASE_NAME);
            } catch (\Exception $e) {
               // return redirect('/copernica/setup')->withWarning($e->getMessage());
            }
        }

        $orderProfileDatas = [
            [
                'name' => 'order_id',
                'type' => 'integer'
            ],
            [
                'name' => 'nationalId',
                'type' => 'text'
            ],
            [
                'name' => 'email',
                'type' => 'email'
            ],
            [
                'name' => 'gender',
                'type' => 'text'
            ],
            [
                'name' => 'firstName',
                'type' => 'text'
            ],
            [
                'name' => 'lastName',
                'type' => 'text'
            ],
            [
                'name' => 'phone',
                'type' => 'text'
            ],
            [
                'name' => 'mobile',
                'type' => 'text'
            ],
            [
                'name' => 'remoteIp',
                'type' => 'text'
            ],
            [
                'name' => 'birthDate',
                'type' => 'empty_date'
            ],
            [
                'name' => 'isCompany',
                'type' => 'integer'
            ],
            [
                'name' => 'companyName',
                'type' => 'text'
            ],
            [
                'name' => 'companyCoCNumber',
                'type' => 'text'
            ],
            [
                'name' => 'companyVatNumber',
                'type' => 'text'
            ],
            [
                'name' => 'addressBillingName',
                'type' => 'text'
            ],
            [
                'name' => 'addressBillingStreet',
                'type' => 'text'
            ],
            [
                'name' => 'addressBillingStreet2',
                'type' => 'text'
            ],
            [
                'name' => 'addressBillingNumber',
                'type' => 'text'
            ],
            [
                'name' => 'addressBillingExtension',
                'type' => 'text'
            ],
            [
                'name' => 'addressBillingZipcode',
                'type' => 'text'
            ],
            [
                'name' => 'addressBillingCity',
                'type' => 'text'
            ],
            [
                'name' => 'addressBillingRegion',
                'type' => 'text'
            ],
            [
                'name' => 'addressBillingCountryCode',
                'type' => 'text'
            ],
            [
                'name' => 'addressBillingCountryTitle',
                'type' => 'text'
            ],
            [
                'name' => 'addressShippingName',
                'type' => 'text'
            ],
            [
                'name' => 'addressShippingStreet',
                'type' => 'text'
            ],
            [
                'name' => 'addressShippingStreet2',
                'type' => 'text'
            ],
            [
                'name' => 'addressShippingNumber',
                'type' => 'text'
            ],
            [
                'name' => 'addressShippingExtension',
                'type' => 'text'
            ],
            [
                'name' => 'addressShippingZipcode',
                'type' => 'text'
            ],
            [
                'name' => 'addressShippingCity',
                'type' => 'text'
            ],
            [
                'name' => 'addressShippingRegion',
                'type' => 'text'
            ],
            [
                'name' => 'addressShippingCountryCode',
                'type' => 'text'
            ],
            [
                'name' => 'addressShippingCountryTitle',
                'type' => 'text'
            ],
            [
                'name' => 'languageCode',
                'type' => 'text'
            ],
            [
                'name' => 'languageTitle',
                'type' => 'text'
            ],
            [
                'name' => 'isConfirmedCustomer',
                'type' => 'integer'
            ],
            [
                'name' => 'customerCreatedAt',
                'type' => 'empty_datetime'
            ],
            [
                'name' => 'customerUpdatedAt',
                'type' => 'empty_datetime'
            ],
            [
                'name' => 'lastOnlineAt',
                'type' => 'empty_datetime'
            ],
            [
                'name' => 'languageLocale',
                'type' => 'text'
            ],
            [
                'name' => 'customerType',
                'type' => 'text'
            ],
        ];

        $databases = (new Copernica)->getAllDatabases();

        $id = (new Copernica)->getDatabaseId($databases['data'], Copernica::ORDER_DATABASE_NAME);

        $collections = (new Copernica)->getAllCollections($id);

        $collectionID = (new Copernica)->getcollectionId($collections['data'], Copernica::ORDER_PERSON_COLLECTION_NAME);

        foreach ($orderProfileDatas as $data) {
            try {
                $status = $database->createCollectionField($data, $collectionID);
            } catch (\Exception $e) {
                //return redirect('/copernica/setup')->withWarning($e->getMessage());
            }
        }
        $orderProductDatas = [
            [
                'name' => 'product_id',
                'type' => 'integer'
            ],
            [
                'name' => 'productTitle',
                'type' => 'text'
            ],
            [
                'name' => 'varientId',
                'type' => 'integer'
            ],
            [
                'name' => 'varientTitle',
                'type' => 'text'
            ],
            [
                'name' => 'quantityOrdered',
                'type' => 'integer'
            ],
            [
                'name' => 'quantityReturned',
                'type' => 'integer'
            ],
            [
                'name' => 'basePriceIncl',
                'type' => 'float'
            ],
            [
                'name' => 'priceIncl',
                'type' => 'float'
            ],
            [
                'name' => 'email',
                'type' => 'email'
            ],

        ];

        $collections = (new Copernica)->getAllCollections($id);

        $collectionID = (new Copernica)->getcollectionId($collections['data'], Copernica::ORDER_PRODUCT_COLLECTION_NAME);

        foreach ($orderProductDatas as $data) {
            try {
                $status = $database->createCollectionField($data, $collectionID);
            } catch (\Exception $e) {
               // return redirect('/copernica/setup')->withWarning($e->getMessage());
            }
        }

        return redirect('/copernica/setup')->withSuccess("Database fields created");
        
    }

    function profileCreate () {
        set_time_limit(120);
        $copernicaAuth = CopernicaAuth::where("user_id", Auth::user()->id)->first();
        if (empty($copernicaAuth) || empty($copernicaAuth->token)) {
            return response()->json( ['success'=>false, 'message' =>"Copernica token not found"], 401 );
        }
        $profile = new Profile();

        $orderPersonEmails = \App\Models\OrderPerson::where('user_id', \Auth::user()->id)->pluck('email');
        $subscribers = \App\Models\Subscriber::where('user_id', \Auth::user()->id)->select('id','profile_id','firstname','lastname','email', 'createdAt', 'updatedAt', 'isConfirmedCustomer', 'languageCode', 'languageTitle', 'optInNewsletter', 'nieuwsbrief')->get();
        if($subscribers->first()) {
            foreach ($subscribers as $subscriber) {
                try {
                    $profileData = $subscriber->toArray();
                    unset($profileData['id']);
                    unset($profileData['profile_id']);
                    if (in_array($subscriber->email, $orderPersonEmails->toArray())) {
                        if(!empty($subscriber->profile_id)) {
                            $delRes = $profile->delete($subscriber->profile_id);
                        }
                        $subscriber->delete();
                    }
                    elseif (!empty($subscriber->profile_id)) {
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
        $checkoutDbId = (new Copernica)->getDatabaseId($databases['data'], Copernica::CHECKOUT_DATABASE_NAME);
        $collections = (new Copernica)->getAllCollections($id);
        $orderCollectionID = (new Copernica)->getcollectionId($collections['data'], Copernica::ORDER_COLLECTION_NAME);
        $productCollectionID = (new Copernica)->getcollectionId($collections['data'], Copernica::ORDER_ROW_COLLECTION_NAME);

        $profile = new Profile();

        $subscribers = \App\Models\OrderPerson::where('user_id', \Auth::user()->id)->select('id', 'profile_id', 'customerId', 'nationalId', 'email', 'gender', 'firstName', 'lastName', 'phone', 'mobile', 'remoteIp', 'birthDate', 'isCompany', 'companyName', 'companyCoCNumber', 'companyVatNumber', 'addressBillingName', 'addressBillingStreet', 'addressBillingStreet2', 'addressBillingNumber', 'addressBillingExtension', 'addressBillingZipcode', 'addressBillingCity', 'addressBillingRegion', 'addressBillingCountryCode', 'addressBillingCountryTitle', 'addressShippingName', 'addressShippingStreet', 'addressShippingStreet2', 'addressShippingNumber', 'addressShippingExtension', 'addressShippingZipcode', 'addressShippingCity', 'addressShippingRegion', 'addressShippingCountryCode', 'addressShippingCountryTitle', 'languageCode', 'languageTitle', 'isConfirmedCustomer', 'customerCreatedAt', 'customerUpdatedAt', 'lastOnlineAt', 'languageLocale', 'customerType', 'optInNewsletter', 'nieuwsbrief')->get();
        $checkouts = \App\Models\Checkout::where('user_id', \Auth::user()->id)->select('id', 'profile_id', 'checkoutId', 'nationalId', 'email', 'gender', 'firstName', 'lastName', 'phone', 'mobile', 'birthDate', 'company', 'coCNumber', 'vatNumber', 'addressBillingName', 'addressBillingStreet', 'addressBillingStreet2', 'addressBillingNumber', 'addressBillingExtension', 'addressBillingZipcode', 'addressBillingCity', 'addressBillingRegion', 'addressBillingCountryCode', 'addressShippingName', 'addressShippingStreet', 'addressShippingStreet2', 'addressShippingNumber', 'addressShippingExtension', 'addressShippingZipcode', 'addressShippingCity', 'addressShippingRegion', 'addressShippingCountryCode', 'createdAt', 'updatedAt', 'customerType', 'optInNewsletter', 'nieuwsbrief')->get();
        
        if ($checkouts->first()) {

            foreach ($checkouts as $checkout) {
                try {
                    $checkoutData = $checkout->toArray();
                    $checkoutId = $checkoutData['checkoutId'];
                    unset($checkoutData['id']);
                    unset($checkoutData['checkoutId']);
                    unset($checkoutData['profile_id']);
                    if (!empty($checkout->profile_id)) {
                        $parameters = array(
                            'fields'    =>  array("checkoutId=={$checkout->checkoutId}"),
                            'async'     =>  1,
                            'create'    =>  0
                        );
                        $profile->update($checkoutData, $checkoutDbId, $parameters);
                        $profileID = $checkout->profile_id;
                    } else {
                        $profileID = $profile->create($checkoutData, $checkoutDbId, true);
                        $checkout->isSaved = true;
                        $checkout->profile_id = $profileID;
                        $checkout->save();
                    }
                } catch (\Exception $e) {
                    return response()->json( ['success'=>false, 'message' =>$e->getMessage()], 401 );
                }
            }
        }
        
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
                        $profile->update($subscriberData, $id, $parameters);
                        $profileID = $subscriber->profile_id;
                    } else {
                        $profileID = $profile->create($subscriberData, $id, true);
                        $subscriber->isSaved = true;
                        $subscriber->profile_id = $profileID;
                        $subscriber->save();
                    }
                    $orders = \App\Models\Order::where('customerId', $customerId)->select('id', 'customerId', 'orderId', 'orderNumber', 'createdAt', 'updatedAt', 'status', 'priceIncl', 'email', 'deliveryDate', 'profile_id')->get();
                    foreach($orders as $order ) {
                        $orderData = $order->toArray();
                        unset($orderData['id']);
                        unset($orderData['profile_id']);
                        if (!empty($order->profile_id)) {
                            $parameters = array(
                                'fields'    =>  array("orderId=={$order->orderId}"),
                                'async'     =>  1,
                                'create'    =>  0
                            );
                            $profile->updateSubprofile($profileID, $orderCollectionID, $orderData, $parameters);
                        } else {
                            $orderID = $profile->createSubprofile($profileID, $orderCollectionID, $orderData, true);
                            $order->isSaved = true;
                            $subscriber->profile_id = $orderID;
                            $order->save();
                        }
                        $products = \App\Models\OrderProduct::where('orderId', $order->orderId)->select('id', 'orderId', 'productId', 'productTitle', 'varientId', 'varientTitle', 'quantityOrdered', 'quantityReturned', 'basePriceIncl', 'priceIncl', 'email', 'profile_id')->get();
                        foreach($products as $product ) {
                            $productData = $product->toArray();
                            unset($productData['id']);
                            unset($productData['profile_id']);
                            if (!empty($product->profile_id)) {
                                $parameters = array(
                                    'fields'    =>  array("id=={$product->profile_id}"),
                                    'async'     =>  1,
                                    'create'    =>  0
                                );
                                $profile->updateSubprofile($profileID, $productCollectionID, $productData, $parameters);
                            } else {
                                $productID = $profile->createSubprofile($profileID, $productCollectionID, $productData, true);
                                $order->isSaved = true;
                                $order->profile_id = $productID;
                                $order->save(); 
                            }
                        }
                    }
                } catch (\Exception $e) {
                    return response()->json( ['success'=>false, 'message' =>$e->getMessage()], 401 );
                }
            }
        }
        return response()->json( ['success'=>true, 'message' =>"Data synced"], 200 );
    }
    
    public function profileCreateFromDiscount()
    {
        return redirect('/copernica/sync')->withWarning("Not yet implemented for discounts");
    }

    public function userDatabaseCreateApi()
    {
        try {
            $copernicaAuth = CopernicaAuth::where("user_id", Auth::user()->id)->first();
            if (empty($copernicaAuth) || empty($copernicaAuth->token)) {
                return response()->json(['success'=> false, "message" => "Copernica token not found"], 401);
            }
            $database = new Database;
            $status = $database->createDatabase(Copernica::USER_DATABASE_NAME);
            $orderProfileDatas = 
            [
                [
                    'name' => 'createdAt',
                    'type' => 'datetime'
                ],
                [
                    'name' => 'updatedAt',
                    'type' => 'datetime'
                ],
                [
                    'name' => 'nationalId',
                    'type' => 'text'
                ],
                [
                    'name' => 'email',
                    'type' => 'email',
                    'displayed' => true
                ],
                [
                    'name' => 'gender',
                    'type' => 'text',
                    'displayed' => true
                ],
                [
                    'name' => 'firstName',
                    'type' => 'text',
                    'displayed' => true
                ],
                [
                    'name' => 'lastName',
                    'type' => 'text',
                    'displayed' => true
                ],
                [
                    'name' => 'phone',
                    'type' => 'text'
                ],
                [
                    'name' => 'mobile',
                    'type' => 'text'
                ],
                [
                    'name' => 'remoteIp',
                    'type' => 'text'
                ],
                [
                    'name' => 'birthDate',
                    'type' => 'empty_date'
                ],
                [
                    'name' => 'isCompany',
                    'type' => 'integer',
                    'displayed' => true
                ],
                [
                    'name' => 'companyName',
                    'type' => 'text'
                ],
                [
                    'name' => 'companyCoCNumber',
                    'type' => 'text'
                ],
                [
                    'name' => 'companyVatNumber',
                    'type' => 'text'
                ],
                [
                    'name' => 'addressBillingName',
                    'type' => 'text'
                ],
                [
                    'name' => 'addressBillingStreet',
                    'type' => 'text'
                ],
                [
                    'name' => 'addressBillingStreet2',
                    'type' => 'text'
                ],
                [
                    'name' => 'addressBillingNumber',
                    'type' => 'text'
                ],
                [
                    'name' => 'addressBillingExtension',
                    'type' => 'text'
                ],
                [
                    'name' => 'addressBillingZipcode',
                    'type' => 'text'
                ],
                [
                    'name' => 'addressBillingCity',
                    'type' => 'text'
                ],
                [
                    'name' => 'addressBillingRegion',
                    'type' => 'text'
                ],
                [
                    'name' => 'addressBillingCountryCode',
                    'type' => 'text'
                ],
                [
                    'name' => 'addressBillingCountryTitle',
                    'type' => 'text'
                ],
                [
                    'name' => 'addressShippingName',
                    'type' => 'text'
                ],
                [
                    'name' => 'addressShippingStreet',
                    'type' => 'text'
                ],
                [
                    'name' => 'addressShippingStreet2',
                    'type' => 'text'
                ],
                [
                    'name' => 'addressShippingNumber',
                    'type' => 'text'
                ],
                [
                    'name' => 'addressShippingExtension',
                    'type' => 'text'
                ],
                [
                    'name' => 'addressShippingZipcode',
                    'type' => 'text'
                ],
                [
                    'name' => 'addressShippingCity',
                    'type' => 'text'
                ],
                [
                    'name' => 'addressShippingRegion',
                    'type' => 'text'
                ],
                [
                    'name' => 'addressShippingCountryCode',
                    'type' => 'text'
                ],
                [
                    'name' => 'addressShippingCountryTitle',
                    'type' => 'text'
                ],
                [
                    'name' => 'languageCode',
                    'type' => 'text'
                ],
                [
                    'name' => 'languageTitle',
                    'type' => 'text'
                ],
                [
                    'name' => 'isConfirmedCustomer',
                    'type' => 'integer',
                    'displayed' => true
                ],
                [
                    'name' => 'customerCreatedAt',
                    'type' => 'empty_datetime',
                    'displayed' => true
                ],
                [
                    'name' => 'customerType',
                    'type' => 'text'
                ],
                [
                    'name' => 'optInNewsletter',
                    'type' => 'integer',
                    'displayed' => true
                ],
                [
                    'name' => 'nieuwsbrief',
                    'type' => 'text',
                    'displayed' => true
                ],
                [
                    'name' => 'customerUpdatedAt',
                    'type' => 'empty_datetime'
                ],
                [
                    'name' => 'lastOnlineAt',
                    'type' => 'empty_datetime'
                ],
                [
                    'name' => 'languageLocale',
                    'type' => 'text'
                ]
            ];

            $databases = $database->getAll();
            $id = $database->getDatabaseId($databases['data'], Copernica::USER_DATABASE_NAME);
            foreach ($orderProfileDatas as $data) {
                $status = $database->createFieldById($data, $id);
            }
        } catch (\Exception $e) {
            return response()->json(['success'=> false, "message" => $e->getMessage()], 401);
        }
        return response()->json(['success'=> true, "message" => "Successfully"]);
        
    }


    public function checkoutDatabaseCreateApi()
    {
        try {
            $copernicaAuth = CopernicaAuth::where("user_id", Auth::user()->id)->first();
            if (empty($copernicaAuth) || empty($copernicaAuth->token)) {
                return response()->json(['success'=> false, "message" => "Copernica token not found"], 401);
            }
            $database = new Database;
            $status = $database->createDatabase(Copernica::CHECKOUT_DATABASE_NAME);
            $orderProfileDatas = 
            [
                [
                    'name' => 'checkoutId',
                    'type' => 'text'
                ],
                [
                    'name' => 'createdAt',
                    'type' => 'datetime'
                ],
                [
                    'name' => 'updatedAt',
                    'type' => 'datetime'
                ],
                [
                    'name' => 'nationalId',
                    'type' => 'text'
                ],
                [
                    'name' => 'email',
                    'type' => 'email',
                    'displayed' => true
                ],
                [
                    'name' => 'gender',
                    'type' => 'text',
                    'displayed' => true
                ],
                [
                    'name' => 'firstName',
                    'type' => 'text',
                    'displayed' => true
                ],
                [
                    'name' => 'lastName',
                    'type' => 'text',
                    'displayed' => true
                ],
                [
                    'name' => 'phone',
                    'type' => 'text'
                ],
                [
                    'name' => 'mobile',
                    'type' => 'text'
                ],
                [
                    'name' => 'birthDate',
                    'type' => 'empty_date'
                ],
                [
                    'name' => 'company',
                    'type' => 'text',
                    'displayed' => true
                ],
                [
                    'name' => 'coCNumber',
                    'type' => 'text'
                ],
                [
                    'name' => 'vatNumber',
                    'type' => 'text'
                ],
                [
                    'name' => 'addressBillingName',
                    'type' => 'text'
                ],
                [
                    'name' => 'addressBillingStreet',
                    'type' => 'text'
                ],
                [
                    'name' => 'addressBillingStreet2',
                    'type' => 'text'
                ],
                [
                    'name' => 'addressBillingNumber',
                    'type' => 'text'
                ],
                [
                    'name' => 'addressBillingExtension',
                    'type' => 'text'
                ],
                [
                    'name' => 'addressBillingZipcode',
                    'type' => 'text'
                ],
                [
                    'name' => 'addressBillingCity',
                    'type' => 'text'
                ],
                [
                    'name' => 'addressBillingRegion',
                    'type' => 'text'
                ],
                [
                    'name' => 'addressBillingCountryCode',
                    'type' => 'text'
                ],
                [
                    'name' => 'addressShippingName',
                    'type' => 'text'
                ],
                [
                    'name' => 'addressShippingStreet',
                    'type' => 'text'
                ],
                [
                    'name' => 'addressShippingStreet2',
                    'type' => 'text'
                ],
                [
                    'name' => 'addressShippingNumber',
                    'type' => 'text'
                ],
                [
                    'name' => 'addressShippingExtension',
                    'type' => 'text'
                ],
                [
                    'name' => 'addressShippingZipcode',
                    'type' => 'text'
                ],
                [
                    'name' => 'addressShippingCity',
                    'type' => 'text'
                ],
                [
                    'name' => 'addressShippingRegion',
                    'type' => 'text'
                ],
                [
                    'name' => 'addressShippingCountryCode',
                    'type' => 'text'
                ],
                [
                    'name' => 'customerType',
                    'type' => 'text'
                ],
                [
                    'name' => 'optInNewsletter',
                    'type' => 'integer',
                    'displayed' => true
                ],
                [
                    'name' => 'nieuwsbrief',
                    'type' => 'text',
                    'displayed' => true
                ],
            ];

            $databases = $database->getAll();
            $id = $database->getDatabaseId($databases['data'], Copernica::CHECKOUT_DATABASE_NAME);
            foreach ($orderProfileDatas as $data) {
                $status = $database->createFieldById($data, $id);
            }
        } catch (\Exception $e) {
            return response()->json(['success'=> false, "message" => $e->getMessage()], 401);
        }
        return response()->json(['success'=> true, "message" => "Successfully"]);
        
    }

    function profileCreateFromSubscriber () {
        set_time_limit(120);
        $copernicaAuth = CopernicaAuth::where("user_id", Auth::user()->id)->first();
        $lightspeedAuth = LightspeedAuth::where("user_id", Auth::user()->id)->first();

        if (empty($lightspeedAuth) || empty($lightspeedAuth->api_key) || empty($copernicaAuth) || empty($copernicaAuth->token)) {
            return redirect('/wizard')->withWarning("Token not found");
        }
        try {
            $subscribers = (new \App\Custom\Lightspeed\Subscriber)->get();
            $orders = (new \App\Custom\Lightspeed\Order)->get();
            $checkouts = (new \App\Custom\Lightspeed\Checkout)->get();
        } catch (\Exception $e) {
            return redirect('/copernica/sync')->withWarning($e->getMessage());
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
                return redirect('/copernica/sync')->withWarning($e->getMessage());
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
                return redirect('/copernica/sync')->withWarning($e->getMessage());
            }
        }

        try {
            $profile = new Profile();

            $orderPersonEmails = \App\Models\OrderPerson::where('user_id', \Auth::user()->id)->pluck('email');
            $subscribers = \App\Models\Subscriber::where('user_id', \Auth::user()->id)->select('id','profile_id','firstname','lastname','email', 'createdAt', 'updatedAt', 'isConfirmedCustomer', 'languageCode', 'languageTitle', 'optInNewsletter', 'nieuwsbrief')->get();
           
            if($subscribers->first()) {
                foreach ($subscribers as $subscriber) {
                    $profileData = $subscriber->toArray();
                    unset($profileData['id']);
                    unset($profileData['profile_id']);
                    if (in_array($subscriber->email, $orderPersonEmails->toArray())) {
                        if(!empty($subscriber->profile_id)) {
                            $delRes = $profile->delete($subscriber->profile_id);
                        }
                        $subscriber->delete();
                    }
                    elseif (!empty($subscriber->profile_id)) {
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
                }
            }

            $databases = (new Copernica)->getAllDatabases();

            $id = (new Copernica)->getDatabaseId($databases['data'], Copernica::USER_DATABASE_NAME);
            $checkoutDbId = (new Copernica)->getDatabaseId($databases['data'], Copernica::CHECKOUT_DATABASE_NAME);
            $collections = (new Copernica)->getAllCollections($id);
            $orderCollectionID = (new Copernica)->getcollectionId($collections['data'], Copernica::ORDER_COLLECTION_NAME);
            $productCollectionID = (new Copernica)->getcollectionId($collections['data'], Copernica::ORDER_ROW_COLLECTION_NAME);

            $profile = new Profile();

            $subscribers = \App\Models\OrderPerson::where('user_id', \Auth::user()->id)->select('id', 'profile_id', 'customerId', 'nationalId', 'email', 'gender', 'firstName', 'lastName', 'phone', 'mobile', 'remoteIp', 'birthDate', 'isCompany', 'companyName', 'companyCoCNumber', 'companyVatNumber', 'addressBillingName', 'addressBillingStreet', 'addressBillingStreet2', 'addressBillingNumber', 'addressBillingExtension', 'addressBillingZipcode', 'addressBillingCity', 'addressBillingRegion', 'addressBillingCountryCode', 'addressBillingCountryTitle', 'addressShippingName', 'addressShippingStreet', 'addressShippingStreet2', 'addressShippingNumber', 'addressShippingExtension', 'addressShippingZipcode', 'addressShippingCity', 'addressShippingRegion', 'addressShippingCountryCode', 'addressShippingCountryTitle', 'languageCode', 'languageTitle', 'isConfirmedCustomer', 'customerCreatedAt', 'customerUpdatedAt', 'lastOnlineAt', 'languageLocale', 'customerType', 'optInNewsletter', 'nieuwsbrief')->get();
            $checkouts = \App\Models\Checkout::where('user_id', \Auth::user()->id)->select('id', 'profile_id', 'checkoutId', 'nationalId', 'email', 'gender', 'firstName', 'lastName', 'phone', 'mobile', 'birthDate', 'company', 'coCNumber', 'vatNumber', 'addressBillingName', 'addressBillingStreet', 'addressBillingStreet2', 'addressBillingNumber', 'addressBillingExtension', 'addressBillingZipcode', 'addressBillingCity', 'addressBillingRegion', 'addressBillingCountryCode', 'addressShippingName', 'addressShippingStreet', 'addressShippingStreet2', 'addressShippingNumber', 'addressShippingExtension', 'addressShippingZipcode', 'addressShippingCity', 'addressShippingRegion', 'addressShippingCountryCode', 'createdAt', 'updatedAt', 'customerType', 'optInNewsletter', 'nieuwsbrief')->get();
        
        } catch (\Exception $e) {
            return redirect('/copernica/sync')->withWarning($e->getMessage());
        }

        if ($checkouts->first()) {

            foreach ($checkouts as $checkout) {
                try {
                    $checkoutData = $checkout->toArray();
                    $checkoutId = $checkoutData['checkoutId'];
                    unset($checkoutData['id']);
                    unset($checkoutData['checkoutId']);
                    unset($checkoutData['profile_id']);
                    if (!empty($checkout->profile_id)) {
                        $parameters = array(
                            'fields'    =>  array("checkoutId=={$checkout->checkoutId}"),
                            'async'     =>  1,
                            'create'    =>  0
                        );
                        $profile->update($checkoutData, $checkoutDbId, $parameters);
                        $profileID = $checkout->profile_id;
                    } else {
                        $profileID = $profile->create($checkoutData, $checkoutDbId, true);
                        $checkout->isSaved = true;
                        $checkout->profile_id = $profileID;
                        $checkout->save();
                    }
                } catch (\Exception $e) {
                    return response()->json( ['success'=>false, 'message' =>$e->getMessage()], 401 );
                }
            }
        }

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
                        $profile->update($subscriberData, $id, $parameters);
                        $profileID = $subscriber->profile_id;
                    } else {
                        $profileID = $profile->create($subscriberData, $id, true);
                        $subscriber->isSaved = true;
                        $subscriber->profile_id = $profileID;
                        $subscriber->save();
                    }
                    $orders = \App\Models\Order::where('customerId', $customerId)->select('id', 'customerId', 'orderId', 'orderNumber', 'createdAt', 'updatedAt', 'status', 'priceIncl', 'email', 'deliveryDate', 'profile_id')->get();
                    foreach($orders as $order ) {
                        $orderData = $order->toArray();
                        unset($orderData['id']);
                        unset($orderData['profile_id']);
                        if (!empty($order->profile_id)) {
                            $parameters = array(
                                'fields'    =>  array("orderId=={$order->orderId}"),
                                'async'     =>  1,
                                'create'    =>  0
                            );
                            $profile->updateSubprofile($profileID, $orderCollectionID, $orderData, $parameters);
                        } else {
                            $orderID = $profile->createSubprofile($profileID, $orderCollectionID, $orderData, true);
                            $order->isSaved = true;
                            $order->profile_id = $orderID;
                            $order->save();

                        }
                        $products = \App\Models\OrderProduct::where('orderId', $order->orderId)->select('id', 'orderId', 'productId', 'productTitle', 'varientId', 'varientTitle', 'quantityOrdered', 'quantityReturned', 'basePriceIncl', 'priceIncl', 'email', 'profile_id')->get();
                        foreach($products as $product ) {
                            $productData = $product->toArray();
                            unset($productData['id']);
                            unset($productData['profile_id']);
                            if (!empty($product->profile_id)) {
                                $parameters = array(
                                    'fields'    =>  array("id=={$product->profile_id}"),
                                    'async'     =>  1,
                                    'create'    =>  0
                                );
                                $profile->updateSubprofile($profileID, $productCollectionID, $productData, $parameters);
                            } else {
                                $productID = $profile->createSubprofile($profileID, $productCollectionID, $productData, true);
                                $product->isSaved = true;
                                $product->profile_id = $productID;
                                $product->save(); 
                            }
                        }
                    }
                } catch (\Exception $e) {
                    return redirect('/copernica/sync')->withWarning($e->getMessage());
                }
            }
        }

        return redirect('/copernica/sync')->withSuccess("Synchronization successfully completed");
    }

    function profileCreateFromOrder () {
        $copernicaAuth = CopernicaAuth::where("user_id", Auth::user()->id)->first();
        if (empty($copernicaAuth) || empty($copernicaAuth->token)) {
            return redirect('/copernica/setup')->withWarning("Copernica token not found");
        }
        // try {
            $databases = (new Copernica)->getAllDatabases();
        // } catch (\Exception $e) {

        // }
        $id = (new Copernica)->getDatabaseId($databases['data'], Copernica::USER_DATABASE_NAME);
        $collections = (new Copernica)->getAllCollections($id);
        $orderCollectionID = (new Copernica)->getcollectionId($collections['data'], Copernica::ORDER_COLLECTION_NAME);
        $productCollectionID = (new Copernica)->getcollectionId($collections['data'], Copernica::ORDER_ROW_COLLECTION_NAME);

        $profile = new Profile();

        // $subscribers = \App\Models\Order::all('id', 'lightspeed_id', 'number', 'createdAt', 'updatedAt', 'status', 'priceIncl', 'email', 'deliveryDate')->toArray();



        // foreach ($subscribers as $subscriber) {
            // try {
                // $subscriber_id = $subscriber['id'];
                // unset($subscriber['id']);
                // $profileID = $profile->create($subscriber, $id, true);
                $subscribers = \App\Models\OrderPerson::where('user_id', \Auth::user()->id)->whereNull('isSaved')->select('id', 'customerId', 'nationalId', 'email', 'gender', 'firstName', 'lastName', 'phone', 'mobile', 'remoteIp', 'birthDate', 'isCompany', 'companyName', 'companyCoCNumber', 'companyVatNumber', 'addressBillingName', 'addressBillingStreet', 'addressBillingStreet2', 'addressBillingNumber', 'addressBillingExtension', 'addressBillingZipcode', 'addressBillingCity', 'addressBillingRegion', 'addressBillingCountryCode', 'addressBillingCountryTitle', 'addressShippingName', 'addressShippingStreet', 'addressShippingStreet2', 'addressShippingNumber', 'addressShippingExtension', 'addressShippingZipcode', 'addressShippingCity', 'addressShippingRegion', 'addressShippingCountryCode', 'addressShippingCountryTitle', 'languageCode', 'languageTitle', 'isConfirmedCustomer', 'customerCreatedAt', 'customerUpdatedAt', 'lastOnlineAt', 'languageLocale', 'customerType', 'optInNewsletter', 'nieuwsbrief')->get();
                if ($subscribers->first()) {

                    foreach ($subscribers as $subscriber) {
                        try {
                            $subscriberData = $subscriber->toArray();
                            $customerId = $subscriberData['customerId'];
                            unset($subscriberData['id']);
                            unset($subscriberData['customerId']);
                            $profileID = $profile->create($subscriberData, $id, true);
                            $subscriber->isSaved = true;
                            $subscriber->save();
                            $orders = \App\Models\Order::where('customerId', $customerId)->select('orderId', 'orderNumber', 'createdAt', 'updatedAt', 'status', 'priceIncl', 'email', 'deliveryDate')->get();
                            foreach($orders as $order ) {
                                $orderRes = $profile->createSubprofile($profileID, $orderCollectionID, $order->toArray(), true);
                                $products = \App\Models\OrderProduct::where('orderId', $order->orderId)->select('productId', 'productTitle', 'varientId', 'varientTitle', 'quantityOrdered', 'quantityReturned', 'basePriceIncl', 'priceIncl', 'email')->get();
                                foreach($products as $product ) {
                                    $productRes = $profile->createSubprofile($profileID, $productCollectionID, $product->toArray(), true);
                                    
                                }
                            }
                        } catch (\Exception $e) {
                            return redirect('/copernica/sync')->withWarning($e->getMessage());
                        }
                    }
                } else {
                    return redirect('/copernica/sync')->withSuccess("All updated");

                }
                // $personRes = $profile->createSubprofile($profileID, $personCollectionID, $person, true);
                // $products = \App\Models\OrderProduct::where('order_id', $subscriber_id)->select('product_id', 'productTitle', 'varientId', 'varientTitle', 'quantityOrdered', 'quantityReturned', 'basePriceIncl', 'priceIncl', 'email')->get()->toArray();
                // foreach ($products as $product) {
                //     $productRes = $profile->createSubprofile($profileID, $productCollectionID, $product, true);

                //     }
                // } catch (\Exception $e) {
                // return redirect('/copernica/sync')->withWarning($e->getMessage());
            // }
        // }


        return redirect('/copernica/sync')->withSuccess("Synchronization successfully completed");
        

    }
 }
