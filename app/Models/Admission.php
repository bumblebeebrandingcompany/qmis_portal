<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Admission extends Model
{
    use HasFactory;

    // protected $appends = ['is_superadmin', 'is_agency', 'is_channel_partner', 'is_channel_partner_manager','is_presales','is_frontoffice'];

    public $table = 'admissions';

    protected $dates = [
        'created_at',
        'updated_at',
    ];


    protected $fillable = [
        'stage_id','notes'
    ];
    public function lead()
    {
        return $this->belongsTo(Lead::class,'lead_id');
    }
    public function application()
    {
        return $this->belongsTo(Application::class,'application_id');
    }

}
