<?php

namespace App\Http\Controllers;

use App\Custom\Lightspeed\Subscriber;
use App\Models\LightspeedAuth;
use Illuminate\Http\Request;

class LightspeedAuthController extends Controller
{

    public function edit()
    {
        $lightspeedAuth = LightspeedAuth::first();
        return view('admin.lightspeedAuth.edit', compact('lightspeedAuth'));
    }

    public function update(Request $request)
    {
        request()->validate([
            'api_key' => 'required',
            'api_secret' => 'required',
        ]);

        $lightspeedAuth = LightspeedAuth::firstOrNew(array('user_id' => \Auth::user()->id));
        $lightspeedAuth->api_key = $request->api_key;
        $lightspeedAuth->api_secret = $request->api_secret;
        $lightspeedAuth->user_id = \Auth::user()->id;
        $lightspeedAuth->save();
        return back()->withSuccess( 'Lightspeed API credentials saved!' );
    }

    public function updateApi(Request $request)
    {
        request()->validate([
            'api_key' => 'required',
            'api_secret' => 'required',
        ]);

        $lightspeedAuth = LightspeedAuth::firstOrNew(array('user_id' => \Auth::user()->id));
        $lightspeedAuth->api_key = $request->api_key;
        $lightspeedAuth->api_secret = $request->api_secret;
        $lightspeedAuth->user_id = \Auth::user()->id;
        $lightspeedAuth->save();
        return response()->json( ['success'=>true] );
    }
}
