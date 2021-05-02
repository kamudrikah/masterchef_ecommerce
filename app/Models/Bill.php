<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bill extends Model
{
    use HasFactory;

    const STATUS_DRAFT = 'draft';
    const STATUS_OPEN = 'open';
    const STATUS_PAID = 'paid';

    protected $fillable = [
        'bill_id',
        'status',
        'user_id',
        'amount',
        'phone',
        'street_address',
        'city',
        'state',
        'postal_code',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
