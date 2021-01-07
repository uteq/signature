<?php

namespace Uteq\Signature;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Uteq\Signature\Signature
 * @method static \Uteq\Signature\Builders\Signature make($action, $payload=null)
 */
class SignatureFacade extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'signature';
    }
}
