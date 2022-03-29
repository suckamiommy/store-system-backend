<?php

namespace App\Actions\Auth;

use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterAction
{
    public array $validateFields = [
        'username' => ['required', 'unique:users', 'min:5', 'max:20'],
        'name' => ['required'],
        'email' => ['required','email', 'unique:users'],
        'password' => ['required','confirmed','min:8']
    ];

    public array $validateMessage = [
//        'username.required' => 'กรุณากรอกชื่อผู้ใช้งาน',
//        'username.unique' => 'ชื่อผู้ใช้งานนี้มีอยู่ในระบบแล้ว',
//        'username.min' => 'ชื่อผู้ใช้งานต้องไม่ต่ำกว่า 5 ตัวอักษร',
//        'username.max' => 'ชื่อผู้ใช้งานต้องไม่เกิน 20 ตัวอักษร',
//        'name.required' => 'กรุณากรอกชื่อ',
//        'email.required' => 'กรุณากรอกอีเมลล์',
//        'email.unique' => 'อีเมลล์นี้มีอยู่ในระบบแล้ว',
//        'email.email' => 'กรุณากรอกอีเมลล์ให้ถูกต้อง',
//        'password.required' => 'กรุณากรอกรหัสผ่าน',
//        'password.min' => 'รหัสผ่านต้องไม่ต่ำกว่า 8 ตัวอักษร',
//        'password.confirmed' => 'ยืนยันรหัสผ่านไม่ตรงกัน',
        'username.required' => 'USERNAME_REQUIRED',
        'username.unique' => 'USERNAME_UNIQUE',
        'username.min' => 'USERNAME_MIN',
        'username.max' => 'USERNAME_MAX',
        'name.required' => 'NAME_REQUIRED',
        'email.required' => 'EMAIL_REQUIRED',
        'email.unique' => 'EMAIL_UNIQUE',
        'email.email' => 'EMAIL_EMAIL',
        'password.required' => 'PASSWORD_REQUIRED',
        'password.min' => 'PASSWORD_MIN',
        'password.confirmed' => 'PASSWORD_CONFIRMATION',
    ];

    public function run($request)
    {
        $user = User::create([
            'username' => $request['username'],
            'name' => $request['name'],
            'email' => $request['email'],
            'password' => Hash::make($request['password'])
        ]);

        return $user;
    }

    public function validateField($request){
        $validator = Validator::make($request, $this->validateFields, $this->validateMessage);

        if ($validator->fails()) {
            return $validator->messages();
        }

        return false;
    }
}
