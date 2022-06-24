<?php


namespace App\Http\Controllers\api\v1;


use App\Http\Controllers\Controller;
use App\Repository\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth as eAuth;
use Illuminate\Support\Facades\Validator;


class AuthController extends Controller
{

    public function unauthorised(){
        return Response::Error([],'Unauthorised Access');
    }

    public function login(Request $request){
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|bail',
            'password' => 'required|bail'
        ]);
        if ($validator->fails()) {
            $error = $validator->errors();
            return Response::Error(Response::ValidationFormater($error->toArray()), "Validation errors found");
        }
        $email = $request->get('email');
        $password = $request->get('password');

        if (eAuth::attempt(['email' => $email, 'password' => $password])) {
            return Response::Success($request->user(),'User Details');
        } else {
            return Response::Error([],"Incorrect password or Inactive account", "Auth error");
        }
    }

}
