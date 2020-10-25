<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LightspeedAuth;

class LightspeedController extends Controller
{
    public function index()
    {
        $lightspeedAuths = LightspeedAuth::all();
        return view('admin.lightspeed.index', ['lightspeedAuths' => $lightspeedAuths]);
    }

    public function create()
    {
        //
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
