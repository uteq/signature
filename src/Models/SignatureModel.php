<?php


namespace Uteq\Signature\Models;

use Illuminate\Database\Eloquent\Model;

class SignatureModel extends Model
{
    public $guarded = ['id'];

    public $table = 'signatures';
}
