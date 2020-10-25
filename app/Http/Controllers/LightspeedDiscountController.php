<?php

namespace App\Http\Controllers;

use App\Custom\Lightspeed\Discount;
use App\Models\LightspeedAuth;
use Illuminate\Support\Facades\Auth;

class LightspeedDiscountController extends Controller
{
    public function import()
    {
        return back()->withWarning("Not implemented yet for discounts");
    }

    public function show()
    {
        $lightspeedAuth = LightspeedAuth::where("user_id", Auth::user()->id)->first();
        if (empty($lightspeedAuth)) {
            return redirect('/')->withWarning("Please set API key and secret in settings");
        }
        try {
            $discounts = (new Discount)->get();
        } catch (\Exception $e) {
            return redirect('/')->withWarning($e->getMessage());
        }
        return view('admin.lightspeed.discount.index', compact('discounts'));
    }

}
