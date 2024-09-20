<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Followup extends Model
{
    use HasFactory;

    //  protected $appends = ['is_superadmin', 'is_agency', 'is_channel_partner', 'is_channel_partner_manager','is_presales','is_front_office'];

    public $table = 'follow_ups';

    protected $dates = [
        'created_at',
        'updated_at',

    ];
    public function users()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    protected $fillable = [
        'parent_stage_id','lead_id',
        'created_by','notes','followup_date','followup_time'
    ];

    // public function timeline()
    // {
    //     return $this->hasMany(LeadTimeline::class);
    // }

    public function lead()
    {
        return $this->belongsTo(Lead::class);
    }
    public function timeline()
    {
        return $this->hasMany(LeadTimeline::class, 'payload');
    }
    public function application()
{
    return $this->belongsTo(Application::class, 'lead_id', 'id'); // Adjust 'lead_id' and 'id' as per your schema
}

}
