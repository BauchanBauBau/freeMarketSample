<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

use Illuminate\Http\Request;//追加

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:50'],
            'nickName' => ['required', 'string', 'max:50', 'unique:users'],
            'email' => ['required', 'string', 'email', 'max:50', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'telNumber' => ['required', 'integer'],
            'postalCode' => ['required', 'regex:/^[0-9]{3}-[0-9]{4}$/','string'],
            'addressPref' => ['required', 'string', 'regex:/^.*(県|道|府)$/'],
            'addressCity' => ['required', 'string', 'regex:/^.*(市|区|町|村)$/'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */

    /*
    protected function create(array $data)記載要領
    ・データベースの該当するテーブルのカラム名と同様に記載する．
    ・app\User.phpでの記載について
        app\User.phpで$fillableを記載する場合は，以下の連想配列のキーを転記する．
        なお，$fillableではなくprotected $guardedを記載する場合は
        原則としてidのみ指定する．)
    */
    protected function create(array $data)
    {
        //dd($data);
        return User::create([
            'name' => $data['name'],
            'nickName' => $data['nickName'],
            'gender' => $data['gender'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'telNumber' => $data['telNumber'],
            'postalCode' => $data['postalCode'],
            'addressPref' => $data['addressPref'],
            'addressCity' => $data['addressCity'],
            'addressOther' => $data['addressOther'],
            'items' => 0, //userIndexでの出品数
        ]);
    }
}
