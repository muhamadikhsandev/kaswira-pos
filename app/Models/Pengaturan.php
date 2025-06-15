<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengaturan extends Model
{
    use HasFactory;

    protected $fillable = [
        'store_name',
        'store_address',
        'store_contact',
        'store_owner',
        'printer_name',
        'receipt_message', // <- diubah dari cashier_name
    ];
}
