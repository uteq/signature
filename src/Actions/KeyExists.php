<?php


namespace Uteq\Signature\Actions;


use Uteq\Signature\Models\SignatureModel;

class KeyExists
{
    public function __invoke($key): bool
    {
         return SignatureModel::query()->where('key', $key)->exists();
    }
}
