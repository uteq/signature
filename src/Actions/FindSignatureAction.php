<?php


namespace Uteq\Signature\Actions;

use Uteq\Signature\Exceptions\ActionNotFoundException;
use Uteq\Signature\Finders\ActionFinder;
use Uteq\Signature\Models\SignatureModel;

class FindSignatureAction
{
    public function __invoke(SignatureModel $signature)
    {
        try {
            return ActionFinder::find($signature->handler);
        } catch (ActionNotFoundException $e) {
            report($e);
            abort(404);
        }
    }
}
