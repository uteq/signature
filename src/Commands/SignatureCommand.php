<?php

namespace Uteq\Signature\Commands;

use Illuminate\Console\Command;
use Uteq\Signature\Models\SignatureModel;

class SignatureCommand extends Command
{
    public $signature = 'signature:clean';

    public $description = 'Deletes all signatures that are expired';

    public function handle(): void
    {
        $signatures = SignatureModel::query()->where('expiration_date', '<', now());
        $signatures->delete();

        $this->comment('Deleted all expired signatures');
    }
}
