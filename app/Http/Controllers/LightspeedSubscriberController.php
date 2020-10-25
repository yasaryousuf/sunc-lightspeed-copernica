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
            return redirect('/')->withWarning("Please set API key and secret in settings");
        }
        try {
            $subscribers = (new Subscriber)->get();
        } catch (\Exception $e) {
            return redirect('/')->withWarning($e->getMessage());
        }

        $newSubscribers = array_map(function($x) { 
            $x['isConfirmedCustomer'] = $x['isConfirmed'];
            $x['languageCode'] = $x['language']['code'];
            $x['languageTitle'] = $x['language']['title'];
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

    public function show()
    {
        $lightspeedAuth = LightspeedAuth::where("user_id", Auth::user()->id)->first();
        if (empty($lightspeedAuth)) {
            return redirect('/')->withWarning("Please set API key and secret in settings");
        }
        try {
            $subscribers = (new Subscriber)->get();
        } catch (\Exception $e) {
            return redirect('/')->withWarning($e->getMessage());
        }

        return view('admin.lightspeed.subscriber.index', compact('subscribers'));
    }
}
