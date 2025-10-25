<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reciept extends Model
{
    //
    use HasFactory;

    protected $table = 'bulk_sms_recipients';

    protected $fillable = [
        'phone_number',
        'firstname',
        'lastname',
        'email',
        'is_active'
    ];
}
