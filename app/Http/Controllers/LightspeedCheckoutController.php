<?php

namespace App\Http\Controllers;

use App\Custom\Lightspeed\Checkout;
use App\Models\LightspeedAuth;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class LightspeedCheckoutController extends Controller
{
    public function show()
    {
        $lightspeedAuth = LightspeedAuth::where("user_id", Auth::user()->id)->first();
        if (empty($lightspeedAuth)) {
            return redirect('/wizard')->withWarning("Please set API key and secret in settings");
        }
        // try {
            $checkouts = (new Checkout)->get();
            dd($checkouts);
        // } catch (\Exception $e) {
        //     return redirect('/')->withWarning($e->getMessage());
        // }
        // $customers = \App\Models\OrderPerson::where('user_id', \Auth::user()->id)->get();
        // $orderPersonEmails = \App\Models\OrderPerson::pluck('email')->all();
        // $subscribers = \App\Models\Subscriber::whereNotIn('email', $orderPersonEmails)->where('user_id', \Auth::user()->id)->get();

        return view('admin.lightspeed.subscriber.index', compact('subscribers', 'customers'));
    }
}
