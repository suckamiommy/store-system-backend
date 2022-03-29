<?php

namespace App\Http\Controllers\Api;

use App\Actions\Auth\LoginAction;
use App\Actions\Auth\RegisterAction;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AuthController extends Controller
{
    public function login(Request $request, LoginAction $loginAction)
    {
        $validator = $loginAction->validateField($request->all());

        if($validator){
            return response()->json($validator, Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $passportRequest = $loginAction->run($request->all());
        $tokenContent = $passportRequest["content"];

        switch ($tokenContent) {
            case 'USERNAME_NOT_MATCH':
                $arrResponse = ["success" => false, "message" => 'LOGIN_NOT_MATCH'];
                break;
            case 'PASSWORD_NOT_MATCH':
                $arrResponse = ["success" => false, "message" => 'PASSWORD_NOT_MATCH'];
                break;
            default:
                $arrResponse = ["success" => false, "message" => 'Unauthenticated'];
        }

        if (!empty($tokenContent['access_token'])) {
            return $passportRequest["response"];
        }

        return response()->json($arrResponse, Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    public function register(Request $request, RegisterAction $registerAction)
    {
        $validator = $registerAction->validateField($request->all());

        if($validator){
            return response()->json($validator, Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $user = $registerAction->run($request->all());

        if (!$user) {
            return response()->json(["success" => false, "message" => 'REGISTER_FAILED'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json(["success" => true, "message" => 'REGISTER_SUCCESS'], Response::HTTP_OK);
    }
}
