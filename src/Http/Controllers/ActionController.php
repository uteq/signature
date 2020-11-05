<?php


namespace Uteq\Signature\Http\Controllers;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Str;
use Uteq\Signature\Models\SignatureModel;

class ActionController
{
    public function __invoke($key)
    {
        if (Str::isUuid($key)) {
            $signature = SignatureModel::all()->where('key', $key)->first();
            if (! $signature) {
                abort(404);
            }
            if (now()->greaterThanOrEqualTo(Carbon::createFromTimeString($signature->expiration_date))) {
                $signature->delete();
                abort(404);
            }
            if (Request::method() === "POST") {
                if (! Hash::check(Request::input('password'), $signature->password)) {
                    abort(403);
                }
            } else {
                if ($signature->password !== null) {
                    return view('signature::password-input');
                }
            }

            $response = app($signature->handler)(json_decode(Crypt::decrypt($signature->payload), true));
            if ($signature->one_time_link) {
                $signature->delete();
            }
            if ($response === null) {
                return redirect('/');
            }

            return $response;
        }
        abort(404);
    }
}
