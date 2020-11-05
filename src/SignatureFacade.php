<?php

namespace Uteq\Signature;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Uteq\Signature\Signature
 */
class SignatureFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'signature';
    }
}
