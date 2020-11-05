<?php

namespace Uteq\Signature\Commands;

use Illuminate\Console\Command;

class SignatureCommand extends Command
{
    public $signature = 'signature';

    public $description = 'My command';

    public function handle(): void
    {
        $this->comment('All done');
    }
}
