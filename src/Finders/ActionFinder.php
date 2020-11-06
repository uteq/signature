<?php


namespace Uteq\Signature\Finders;

use Uteq\Signature\Exceptions\ActionNotFoundException;

class ActionFinder
{
    public static function find($handler)
    {
        if (class_exists($handler)) {
            return $handler;
        }
        if (class_exists(config('signature.actions.' . $handler))) {
            return config('signature.actions.' . $handler);
        }
        throw new ActionNotFoundException("[Signature] Could not find" . $handler . " in the config (signature.actions)");
    }
}
