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
            $this->databaseCreate(Copernica::ORDER_DATABASE_NAME);
        } catch (\Exception $e) {
            return redirect('/copernica/setup')->withWarning($e->getMessage());
        }
        return redirect('/copernica/setup')->withSuccess("Order database created");
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
            return redirect('/copernica/setup')->withWarning("Copernica token not found");
        }
        $databases = (new Copernica)->getAllDatabases();
        $databaseID = (new Copernica)->getDatabaseId($databases['data'], Copernica::ORDER_DATABASE_NAME);
        $database = new Database;
        $status = $database->createCollection($databaseID, $collection_name);
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

    function profileCreateFromSubscriber () {
        $copernicaAuth = CopernicaAuth::where("user_id", Auth::user()->id)->first();
        if (empty($copernicaAuth) || empty($copernicaAuth->token)) {
            return redirect('/copernica/setup')->withWarning("Copernica token not found");
        }
        $profile = new Profile();

        $subscribers = \App\Models\Subscriber::all('firstname','lastname','email', 'createdAt', 'updatedAt', 'isConfirmed')->toArray();

        foreach ($subscribers as $subscriber) {
            // try {
                $status = $profile->create($subscriber, Copernica::SUBSCRIBER_DATABASE_NAME);
            // } catch (\Exception $e) {
                //return redirect('/copernica/sync')->withWarning($e->getMessage());
            // }
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
        $id = (new Copernica)->getDatabaseId($databases['data'], Copernica::ORDER_DATABASE_NAME);
        $collections = (new Copernica)->getAllCollections($id);
        $personCollectionID = (new Copernica)->getcollectionId($collections['data'], Copernica::ORDER_PERSON_COLLECTION_NAME);
        $productCollectionID = (new Copernica)->getcollectionId($collections['data'], Copernica::ORDER_PRODUCT_COLLECTION_NAME);

        $profile = new Profile();

        $subscribers = \App\Models\Order::all('id', 'lightspeed_id', 'number', 'createdAt', 'updatedAt', 'status', 'priceIncl', 'email', 'deliveryDate')->toArray();


        foreach ($subscribers as $subscriber) {
            try {
                $subscriber_id = $subscriber['id'];
                unset($subscriber['id']);
                $profileID = $profile->create($subscriber, $id);
                $person = \App\Models\OrderPerson::where('order_id', $subscriber_id)->select('order_id', 'nationalId', 'email', 'gender', 'firstName', 'lastName', 'phone', 'mobile', 'remoteIp', 'birthDate', 'isCompany', 'companyName', 'companyCoCNumber', 'companyVatNumber', 'addressBillingName', 'addressBillingStreet', 'addressBillingStreet2', 'addressBillingNumber', 'addressBillingExtension', 'addressBillingZipcode', 'addressBillingCity', 'addressBillingRegion', 'addressBillingCountryCode', 'addressBillingCountryTitle', 'addressShippingName', 'addressShippingStreet', 'addressShippingStreet2', 'addressShippingNumber', 'addressShippingExtension', 'addressShippingZipcode', 'addressShippingCity', 'addressShippingRegion', 'addressShippingCountryCode', 'addressShippingCountryTitle', 'languageCode', 'languageTitle', 'isConfirmedCustomer', 'customerCreatedAt', 'customerUpdatedAt', 'lastOnlineAt', 'languageLocale', 'customerType')->first()->toArray();
                $personRes = $profile->createSubprofile($profileID, $personCollectionID, $person);
                $products = \App\Models\OrderProduct::where('order_id', $subscriber_id)->select('product_id', 'productTitle', 'varientId', 'varientTitle', 'quantityOrdered', 'quantityReturned', 'basePriceIncl', 'priceIncl', 'email')->get()->toArray();
                foreach ($products as $product) {
                    $productRes = $profile->createSubprofile($profileID, $productCollectionID, $product);

                    }
                } catch (\Exception $e) {
                return redirect('/copernica/sync')->withWarning($e->getMessage());
            }
        }


        return redirect('/copernica/sync')->withSuccess("Profile created");
        

    }
 }
