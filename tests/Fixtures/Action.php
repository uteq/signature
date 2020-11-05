<?php


namespace Uteq\Signature\Tests\Fixtures;


class Action
{
    public function __invoke($payload)
    {
        return json_encode($payload);
    }

}
