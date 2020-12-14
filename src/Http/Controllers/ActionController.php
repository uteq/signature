<?php


namespace Uteq\Signature\Http\Controllers;

use Uteq\Signature\Actions\DeleteSignatureAction;
use Uteq\Signature\Actions\FindSignatureAction;
use Uteq\Signature\Actions\HandleSignatureAction;
use Uteq\Signature\Models\SignatureModel;

class ActionController
{
    public function __invoke(SignatureModel $signature)
    {
        if ($signature->isExpired()) {
            app(DeleteSignatureAction::class)($signature);
            abort(404);
        }

        if (!app(FindSignatureAction::class)($signature)) {
            abort(404);
        }

        if ($signature->password !== null && !session('signature.validated.' . $signature->key)) {
            return view('signature::password-input', ['key' => $signature->key]);
        }

        return app(HandleSignatureAction::class)($signature, app(FindSignatureAction::class)($signature));
    }
}
