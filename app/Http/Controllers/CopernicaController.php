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

    public function setup()
    {
        return view('admin.copernica.setup');
    }

    public function sync()
    {
        $copernicaAuth = CopernicaAuth::where("user_id", Auth::user()->id)->firstOrfail();
        if (empty($copernicaAuth->token)) {
            return redirect('/')->withWarning("Copernica token not found");
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
                    'type' => 'string',
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
                    'type' => 'integer',
                    'displayed' => true
                ],
                [
                    'name' => 'productTitle',
                    'type' => 'string',
                    'displayed' => true
                ],
                [
                    'name' => 'varientId',
                    'type' => 'integer',
                    'displayed' => true
                ],
                [
                    'name' => 'varientTitle',
                    'type' => 'string',
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
                'type' => 'string'
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
        foreach ($orderProfileDatas as $data) {
            try {
                $status = $database->createField($data, Copernica::USER_DATABASE_NAME);
            } catch (\Exception $e) {
            
            }
        }
        
        return response()->json(['success'=> true, "message" => "subscriber fields created"], 200);
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
                'type' => 'string'
            ],
            [
                'name' => 'email',
                'type' => 'email'
            ],
            [
                'name' => 'gender',
                'type' => 'string'
            ],
            [
                'name' => 'firstName',
                'type' => 'string'
            ],
            [
                'name' => 'lastName',
                'type' => 'string'
            ],
            [
                'name' => 'phone',
                'type' => 'string'
            ],
            [
                'name' => 'mobile',
                'type' => 'string'
            ],
            [
                'name' => 'remoteIp',
                'type' => 'string'
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
                'type' => 'string'
            ],
            [
                'name' => 'companyCoCNumber',
                'type' => 'string'
            ],
            [
                'name' => 'companyVatNumber',
                'type' => 'string'
            ],
            [
                'name' => 'addressBillingName',
                'type' => 'string'
            ],
            [
                'name' => 'addressBillingStreet',
                'type' => 'string'
            ],
            [
                'name' => 'addressBillingStreet2',
                'type' => 'string'
            ],
            [
                'name' => 'addressBillingNumber',
                'type' => 'string'
            ],
            [
                'name' => 'addressBillingExtension',
                'type' => 'string'
            ],
            [
                'name' => 'addressBillingZipcode',
                'type' => 'string'
            ],
            [
                'name' => 'addressBillingCity',
                'type' => 'string'
            ],
            [
                'name' => 'addressBillingRegion',
                'type' => 'string'
            ],
            [
                'name' => 'addressBillingCountryCode',
                'type' => 'string'
            ],
            [
                'name' => 'addressBillingCountryTitle',
                'type' => 'string'
            ],
            [
                'name' => 'addressShippingName',
                'type' => 'string'
            ],
            [
                'name' => 'addressShippingStreet',
                'type' => 'string'
            ],
            [
                'name' => 'addressShippingStreet2',
                'type' => 'string'
            ],
            [
                'name' => 'addressShippingNumber',
                'type' => 'string'
            ],
            [
                'name' => 'addressShippingExtension',
                'type' => 'string'
            ],
            [
                'name' => 'addressShippingZipcode',
                'type' => 'string'
            ],
            [
                'name' => 'addressShippingCity',
                'type' => 'string'
            ],
            [
                'name' => 'addressShippingRegion',
                'type' => 'string'
            ],
            [
                'name' => 'addressShippingCountryCode',
                'type' => 'string'
            ],
            [
                'name' => 'addressShippingCountryTitle',
                'type' => 'string'
            ],
            [
                'name' => 'languageCode',
                'type' => 'string'
            ],
            [
                'name' => 'languageTitle',
                'type' => 'string'
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
                'type' => 'string'
            ],
            [
                'name' => 'customerType',
                'type' => 'string'
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
                'type' => 'string'
            ],
            [
                'name' => 'varientId',
                'type' => 'integer'
            ],
            [
                'name' => 'varientTitle',
                'type' => 'string'
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
                'type' => 'string'
            ],
            [
                'name' => 'email',
                'type' => 'email'
            ],
            [
                'name' => 'gender',
                'type' => 'string'
            ],
            [
                'name' => 'firstName',
                'type' => 'string'
            ],
            [
                'name' => 'lastName',
                'type' => 'string'
            ],
            [
                'name' => 'phone',
                'type' => 'string'
            ],
            [
                'name' => 'mobile',
                'type' => 'string'
            ],
            [
                'name' => 'remoteIp',
                'type' => 'string'
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
                'type' => 'string'
            ],
            [
                'name' => 'companyCoCNumber',
                'type' => 'string'
            ],
            [
                'name' => 'companyVatNumber',
                'type' => 'string'
            ],
            [
                'name' => 'addressBillingName',
                'type' => 'string'
            ],
            [
                'name' => 'addressBillingStreet',
                'type' => 'string'
            ],
            [
                'name' => 'addressBillingStreet2',
                'type' => 'string'
            ],
            [
                'name' => 'addressBillingNumber',
                'type' => 'string'
            ],
            [
                'name' => 'addressBillingExtension',
                'type' => 'string'
            ],
            [
                'name' => 'addressBillingZipcode',
                'type' => 'string'
            ],
            [
                'name' => 'addressBillingCity',
                'type' => 'string'
            ],
            [
                'name' => 'addressBillingRegion',
                'type' => 'string'
            ],
            [
                'name' => 'addressBillingCountryCode',
                'type' => 'string'
            ],
            [
                'name' => 'addressBillingCountryTitle',
                'type' => 'string'
            ],
            [
                'name' => 'addressShippingName',
                'type' => 'string'
            ],
            [
                'name' => 'addressShippingStreet',
                'type' => 'string'
            ],
            [
                'name' => 'addressShippingStreet2',
                'type' => 'string'
            ],
            [
                'name' => 'addressShippingNumber',
                'type' => 'string'
            ],
            [
                'name' => 'addressShippingExtension',
                'type' => 'string'
            ],
            [
                'name' => 'addressShippingZipcode',
                'type' => 'string'
            ],
            [
                'name' => 'addressShippingCity',
                'type' => 'string'
            ],
            [
                'name' => 'addressShippingRegion',
                'type' => 'string'
            ],
            [
                'name' => 'addressShippingCountryCode',
                'type' => 'string'
            ],
            [
                'name' => 'addressShippingCountryTitle',
                'type' => 'string'
            ],
            [
                'name' => 'languageCode',
                'type' => 'string'
            ],
            [
                'name' => 'languageTitle',
                'type' => 'string'
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
                'type' => 'string'
            ],
            [
                'name' => 'customerType',
                'type' => 'string'
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
                'type' => 'string'
            ],
            [
                'name' => 'varientId',
                'type' => 'integer'
            ],
            [
                'name' => 'varientTitle',
                'type' => 'string'
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
        $copernicaAuth = CopernicaAuth::where("user_id", Auth::user()->id)->firstOrfail();
        if (empty($copernicaAuth->token)) {
            return redirect('/')->withWarning("Copernica token not found");
        }
        $profile = new Profile();

        $fields = [
            'firstname' =>  'John',
            'lastname'  =>  'Doe',
            'email'     =>  'johndoe@example.com'
        ];

        $status = $profile->create($fields);

        if (empty($status)) {
            return redirect('/')->withWarning("Error: profile might be exists");
        } elseif (!empty($status)) {
            return redirect('/')->withSuccess("Profile created");
        }

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
        } catch (\Exception $e) {
            return response()->json(['success'=> false, "message" => $e->getMessage()], 401);
        }
        return response()->json(['success'=> true, "message" => "Successfully"]);
        
    }

    function profileCreateFromSubscriber () {
        $copernicaAuth = CopernicaAuth::where("user_id", Auth::user()->id)->first();
        if (empty($copernicaAuth) || empty($copernicaAuth->token)) {
            return redirect('/copernica/setup')->withWarning("Copernica token not found");
        }
        $profile = new Profile();

        $subscribers = \App\Models\Subscriber::where('user_id', \Auth::user()->id)->whereNull('isSaved')->select('id','firstname','lastname','email', 'createdAt', 'updatedAt', 'isConfirmedCustomer', 'languageCode', 'languageTitle', 'optInNewsletter', 'nieuwsbrief')->get();
        if($subscribers->first()) {
            foreach ($subscribers as $subscriber) {
                try {
                    $profileData = $subscriber->toArray();
                    unset($profileData['id']);
                    $status = $profile->create($profileData, Copernica::USER_DATABASE_NAME);
                } catch (\Exception $e) {
                    return redirect('/copernica/sync')->withWarning($e->getMessage());
                }
                $subscriber->isSaved = true;
                $subscriber->save();
            }
        } else {
            return redirect('/copernica/sync')->withSuccess("All updated");
        }


        return redirect('/copernica/sync')->withSuccess("Profile created");
        

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


        return redirect('/copernica/sync')->withSuccess("Profile created");
        

    }
 }
