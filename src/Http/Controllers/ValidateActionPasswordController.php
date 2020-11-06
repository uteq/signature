<?php


namespace Uteq\Signature\Http\Controllers;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Session;
use Uteq\Signature\Models\SignatureModel;

class ValidateActionPasswordController
{
    public function __invoke(SignatureModel $signature)
    {
        $errors = null;

        if (! Hash::check(Request::input('password'), $signature->password)) {
            $errors = ['password' => "Password is incorrect"];
        } else {
            Session::put('signature.validated.' . $signature->key, true);
        }

        return redirect(route('signature.action_route', $signature->key))->withErrors($errors);
    }
}
