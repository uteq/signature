<?php


namespace Uteq\Signature\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Uteq\Signature\Database\Factories\SignatureModelFactory;

class SignatureModel extends Model
{
    use HasFactory;

    public $table = 'signatures';
    protected $dates = ['expiration_date'];

    public function isExpired(): bool
    {
        return now()->greaterThanOrEqualTo($this->expiration_date);
    }

    protected static function newFactory(): SignatureModelFactory
    {
        return SignatureModelFactory::new();
    }
}
