<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailLog extends Model
{
    use HasFactory;

    // protected $appends = ['is_superadmin', 'is_agency', 'is_channel_partner', 'is_channel_partner_manager','is_presales','is_frontoffice'];

    public $table = 'email_logs';

    protected $dates = [
        'created_at',
        'updated_at',
    ];
    protected $fillable = ['lead_id', 'error', 'status', 'page'];

    public function lead()
    {
        return $this->belongsTo(Lead::class,'lead_id');
    }

}
