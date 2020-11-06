<?php


namespace Uteq\Signature\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class SignatureModel extends Model
{
    public $guarded = ['id'];

    public $table = 'signatures';
    protected $dates = ['expiration_date'];

    public function isExpired()
    {
        return now()->greaterThanOrEqualTo($this->expiration_date);
    }
}
