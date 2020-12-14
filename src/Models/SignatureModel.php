<?php


namespace Uteq\Signature\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Uteq\Signature\Database\Factories\SignatureModelFactory;

class SignatureModel extends Model
{
    use HasFactory;

    public $guarded = ['id'];

    public $table = 'signatures';
    protected $dates = ['expiration_date'];

    public function isExpired()
    {
        return now()->greaterThanOrEqualTo($this->expiration_date);
    }

    protected static function newFactory()
    {
        return SignatureModelFactory::new();
    }
}
