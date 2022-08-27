<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $guarded = [
        'id'
    ];

    public function order()
    {
        return $this->hasMany(Order::class, 'kode_transaksi', 'kode_transaksi');
    }
    public function users()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}
