<?php

namespace App\Http\Controllers;

use App\Custom\Lightspeed\Order;
use App\Models\LightspeedAuth;
use Illuminate\Support\Facades\Auth;

class LightspeedOrderController extends Controller
{
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
