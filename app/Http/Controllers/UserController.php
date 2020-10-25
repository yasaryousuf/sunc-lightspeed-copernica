<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\LightspeedAuth;
use App\Models\CopernicaAuth;

class UserController extends Controller
{

    public function index()
    {
        $users = User::whereNull("is_admin")->get();
        return view('admin.users.index', ['users' => $users]);
    }

    public function wizard()
    {
        $lightspeedAuth = LightspeedAuth::where('user_id', \Auth::user()->id)->first();
        $copernicaAuth = CopernicaAuth::where('user_id', \Auth::user()->id)->first();
        return view('admin.users.wizard', ['lightspeedAuth' => $lightspeedAuth, 'copernicaAuth' => $copernicaAuth, "warning" => "Please complete setup"]);
    }

    public function postWizard(Request $request)
    {
        return \redirect('/')->withSuccess("Setup is completed");
    }

    public function store(Request $request)
    {
        //
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }
}
