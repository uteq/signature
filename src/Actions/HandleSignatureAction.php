<?php


namespace Uteq\Signature\Actions;

use Illuminate\Support\Facades\Crypt;
use Uteq\Signature\Models\SignatureModel;

class HandleSignatureAction
{
    public function __invoke(SignatureModel $signature, $handler)
    {
        $response = app($handler)(json_decode(Crypt::decrypt($signature->payload), true));

        if ($signature->one_time_link) {
            $signature->delete();
        }
        if ($response === null) {
            return redirect('/');
        }

        return $response;
    }
}
