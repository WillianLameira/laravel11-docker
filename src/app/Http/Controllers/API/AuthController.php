<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Laravel\Passport\AuthCode;

use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /** register new account */
    public function register(Request $request)
    {
        // Verifica se o token do usuário autenticado tem o escopo necessário
        if (!$request->user()->tokenCan('register-user')) {
            return response()->json([
                'code' => '403',
                'status' => 'error',
                'message' => 'Unauthorized - Insufficient scope'
            ], 403);
        }

        $request->validate([
            'name'     => 'required|min:4',
            'email'    => 'required|email',
            'password' => 'required|min:8',
        ]);

        $dt        = Carbon::now();
        $join_date = $dt->toDayDateTimeString();

        $user = new User();
        $user->name         = $request->name;
        $user->email        = $request->email;
        $user->password     = Hash::make($request->password);
        $user->scopes       = $request->scopes;
        $user->save();

        $data = [];
        $data['code']           = '200';
        $data['status']         = 'success';
        $data['message']        = 'success Register';
        return response()->json($data);
    }

    /**
     * Login Req
     */
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|string',
            'password' => 'required|string',
        ]);

        try {

            $email     = $request->email;
            $password  = $request->password;

            if (Auth::attempt(['email' => $email, 'password' => $password])) {
                $user = Auth::User();
                $accessToken = $user->createToken($user->email,$user->scopes)->accessToken;

                $data = [];
                $data['code']  = '200';
                $data['status']         = 'success';
                $data['message']        = 'success Login';
                $data['user_infor']     = $user;
                $data['access_token']    = $accessToken;
                return response()->json($data);
            } else {
                $data = [];
                $data['code']  = '401';
                $data['status']         = 'error';
                $data['message']        = 'Unauthorised';

                return response()->json($data);
            }
        } catch (\Exception $e) {
            \Log::info($e);
            $data = [];
            $data['code']  = '401';
            $data['status']         = 'error';
            $data['message']        = 'fail Login';
            return response()->json($data);
        }
    }

    /** user info */
    public function userInfo()
    {
        try {
            $userDataList = User::latest()->paginate(10);
            $data = [];
            $data['code']  = '200';
            $data['status']         = 'success';
            $data['message']        = 'success get user list';
            $data['data_user_list'] = $userDataList;
            return response()->json($data);
        } catch (\Exception $e) {
            \Log::info($e);
            $data = [];
            $data['code']  = '400';
            $data['status']         = 'error';
            $data['message']        = 'fail get user list';
            return response()->json($data);
        }
    }
}
