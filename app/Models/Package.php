<?php

// app/Models/Package.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    use HasFactory;

    protected $fillable = [
        'plan_id',
        'type',
        'amount',
        'class',
        'offer',
        'transport_price',
        'admission_fees',
        'waiver_term2',
        'waiver_term3',
        'kid_gym_waiver',
        'total_amount',
        'benefits_availed'
    ];

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }
}

