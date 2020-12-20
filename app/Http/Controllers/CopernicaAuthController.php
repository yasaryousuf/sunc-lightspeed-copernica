<?php

namespace App\Http\Controllers;

use App\Custom\Lightspeed\Subscriber;
use App\Models\CopernicaAuth;
use Illuminate\Http\Request;

class CopernicaAuthController extends Controller
{

    public function edit()
    {
        $copernicaAuth = CopernicaAuth::where('user_id', \Auth::user()->id)->first();
        return view('admin.copernicaAuth.edit', compact('copernicaAuth'));
    }

    public function update(Request $request)
    {
            request()->validate([
                'api_key' => 'required',
                'api_secret' => 'required',
                'token' => 'required',
            ]);

            $copernicaAuth = CopernicaAuth::firstOrNew(array('user_id' => \Auth::user()->id));
            $copernicaAuth->api_key = $request->api_key;
            $copernicaAuth->api_secret = $request->api_secret;
            $copernicaAuth->token = $request->token;
            $copernicaAuth->user_id = \Auth::user()->id;
            $copernicaAuth->save();

        return back()->withSuccess( 'Copernica API credentials saved!' );
    }

    public function updateApi(Request $request)
    {
        try {
            request()->validate([
                'api_key' => 'required',
                'api_secret' => 'required',
                'token' => 'required',
            ]);

            $copernicaAuth = CopernicaAuth::firstOrNew(array('user_id' => \Auth::user()->id));
            $copernicaAuth->api_key = $request->api_key;
            $copernicaAuth->api_secret = $request->api_secret;
            $copernicaAuth->token = $request->token;
            $copernicaAuth->user_id = \Auth::user()->id;
            $copernicaAuth->save();
            (new CopernicaController)->userDatabaseCreateApi();
        } catch (\Exception $e) {
            return response()->json(['success'=> false, "message" => $e->getMessage()], 401);
        }
        return response()->json( ['success'=>true] );
    }
}
