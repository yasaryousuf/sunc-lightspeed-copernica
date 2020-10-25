<?php
 
namespace App\Http\Controllers;
 
use Illuminate\Http\Request;
use Validator,Redirect,Response;
Use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Session;
use App\Models\CopernicaAuth;
use App\Models\LightspeedAuth;
 
class AuthController extends Controller
{

    function __construct()
    {
    }
    
    public function success()
    {
        return view('success');
    }

    public function index()
    {
        return view('admin.auth.login');
    }  
 
    public function registration()
    {
        return view('admin.auth.register');
    }
     
    public function postLogin(Request $request)
    {
        request()->validate([
        'email' => 'required',
        'password' => 'required',
        ]);
 
        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {
            $copernicaAuth = CopernicaAuth::where("user_id", Auth::user()->id)->first();
            $lightspeedAuth = LightspeedAuth::where("user_id", Auth::user()->id)->first();

            if (empty($lightspeedAuth) || empty($lightspeedAuth->api_key) || empty($copernicaAuth) || empty($copernicaAuth->token)) {
                return redirect('/wizard');
            }
            return redirect()->intended('dashboard');
        }

        return Redirect::to("login")->withSuccess('Opps! You have entered invalid credentials');
    }

    public function postRegistration(Request $request)
    {
        request()->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
        ]);
         
        $data = $request->all();
 
        try {
            $check = $this->create($data);
        } catch (\Exception $e) {
            return Redirect::to("registration")->withSuccess('Something went wrong.');
        }
        
       
        return Redirect::to("login")->withSuccess('You have Successfully registered. Now, login using email and password.');
    }
     
    public function dashboard()
    {
        return view('admin.dashboard');
    }
 
    public function create(array $data)
    {
      return User::create([
        'name' => $data['name'],
        'email' => $data['email'],
        'password' => Hash::make($data['password'])
      ]);
    }
     
    public function logout() {
        Session::flush();
        Auth::logout();
        return Redirect('login');
    }
     
    public function copernica()
    {
        return view('admin.copernica');
    }
}