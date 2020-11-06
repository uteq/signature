<?php


namespace Uteq\Signature\Http\Controllers;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Str;
use Uteq\Signature\Actions\FindSignatureAction;
use Uteq\Signature\Actions\HandleSignatureAction;
use Uteq\Signature\Exceptions\ActionNotFoundException;
use Uteq\Signature\Finders\ActionFinder;
use Uteq\Signature\Models\SignatureModel;

class ActionController
{
    public function __invoke(SignatureModel $signature)
    {
        if ($signature->isExpired()) {
            $signature->delete();
            abort(404);
        }

        $handler = app(FindSignatureAction::class)($signature);

        if ($signature->password !== null && ! session('signature.validated.' . $signature->key)) {
            return view('signature::password-input', ['key' => $signature->key]);
        }

        return app(HandleSignatureAction::class)($signature, $handler);

    }
}
