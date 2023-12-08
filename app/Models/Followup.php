<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Followup extends Model
{
    use HasFactory;

    protected $appends = ['is_superadmin', 'is_client', 'is_agency', 'is_channel_partner', 'is_channel_partner_manager',];

    public $table = 'lead_follow_ups';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
        'follow_up_date',
        'follow_up_time',
        'notes'
    ];
    public function users()
    {
        return $this->belongsTo(User::class, 'user_id');
    }


}
