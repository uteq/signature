<?php


namespace Uteq\Signature\Actions;


use Uteq\Signature\Models\SignatureModel;

class DeleteSignatureAction
{
    public function __invoke(SignatureModel $signature)
    {
        if ($signature->group != null) {
            SignatureModel::query()->where('group', $signature->group)->delete();
        } else {
            $signature->delete();
        }
    }

}
