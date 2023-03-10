<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    use HasFactory;

    protected $fillable = ['amount', 'state'];

    public function repayments()
    {
        return $this->hasMany(Repayment::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
