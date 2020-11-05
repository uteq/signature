<?php

namespace Uteq\Signature;


class Signature
{
    public function make($handler, array $payload = []): Builders\Signature
    {
        return new \Uteq\Signature\Builders\Signature($handler, $payload);
    }
}
