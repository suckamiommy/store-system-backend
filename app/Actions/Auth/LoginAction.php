<?php

namespace App\Actions\Auth;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Passport\Client;

class LoginAction
{
    public array $validateFields = [
        'username' => ['required', 'min:5', 'max:20'],
        'password' => ['required','min:8']
    ];

    public array $validateMessage = [
        'username.required' => 'กรุณากรอกชื่อผู้ใช้งาน',
        'username.min' => 'ชื่อผู้ใช้งานต้องไม่ต่ำกว่า 5 ตัวอักษร',
        'username.max' => 'ชื่อผู้ใช้งานต้องไม่เกิน 20 ตัวอักษร',
        'password.required' => 'กรุณากรอกรหัสผ่าน',
        'password.min' => 'รหัสผ่านต้องไม่ต่ำกว่า 8 ตัวอักษร',
    ];

    public function run($request)
    {
        $user = User::where('username', $request['username'])->first();

        if(!$user){
            return [
                "response" => '',
                "content" => 'USERNAME_NOT_MATCH'
            ];
        }else{
            if(!Hash::check($request['password'], $user->password)){
                return [
                    "response" => '',
                    "content" => 'PASSWORD_NOT_MATCH'
                ];
            }
        }

        $passwordGrantClient = Client::where('password_client', 1)->first();

        $data = [
            'grant_type' => 'password',
            'client_id' => $passwordGrantClient->id,
            'client_secret' => $passwordGrantClient->secret,
            'username' => $request['username'],
            'password' => $request['password'],
            'scope' => '*'
        ];

        $tokenRequest = Request::create('/oauth/token', 'post', $data);

        $tokenResponse = app()->handle($tokenRequest);
        $contentString = $tokenResponse->content();

        return [
            "response" => $tokenResponse,
            "content" => json_decode($contentString, true)
        ];
    }

    public function validateField($request){
        $validator = Validator::make($request, $this->validateFields, $this->validateMessage);

        if ($validator->fails()) {
            return $validator->messages();
        }

        return false;
    }
}
