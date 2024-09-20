<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    use HasFactory;

    // protected $appends = ['is_superadmin', 'is_agency', 'is_channel_partner', 'is_channel_partner_manager','is_presales','is_frontoffice'];

    public $table = 'applications';

    protected $dates = [
        'created_at',
        'updated_at',
    ];
    protected $fillable = ['lead_id', 'application_no', 'stage_id'];
    public function lead()
    {
        return $this->belongsTo(Lead::class,'lead_id');
    }
    public function application()
    {
        return $this->hasMany(Application::class,'application_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class,'who_assigned');
    }

    public function users()
    {
        return $this->belongsTo(User::class,'for_whom');
    }
    public function followups()
    {
        return $this->hasMany(Followup::class, 'lead_id', 'id'); // Adjust 'lead_id' and 'id' as per your schema
    }

}
