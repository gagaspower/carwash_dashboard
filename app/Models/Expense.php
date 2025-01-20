<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    protected $table = 'expenses';

    protected $fillable = [
        'tanggal_pencatatan',
        'jenis_pengeluaran',
        'jumlah_pengeluaran',
        'catatan_pengeluaran',
        'created_user',
        'updated_user',
        'user_id'
    ];

    protected $hidden = ['created_at', 'updated_at'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
