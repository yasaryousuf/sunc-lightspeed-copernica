<?php

namespace App\Http\Controllers;

use App\Custom\Lightspeed\Subscriber;
use App\Models\LightspeedAuth;
use Illuminate\Support\Facades\Auth;

class LightspeedSubscriberController extends Controller
{
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
