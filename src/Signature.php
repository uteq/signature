<?php

namespace Uteq\Signature;

class Signature
{
    public function make($handler, array $payload = []): Builders\Signature
    {
        if (config('signature.hidden_actions')) {
            if (config('signature.actions.' . $handler) === null) {
                throw new \Exception("[Signature] Could not find action '". $handler ."'");
            }
        }

        return new \Uteq\Signature\Builders\Signature($handler, $payload);
    }
}
