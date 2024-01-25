<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Walkin extends Model
{
    use  HasFactory;
    protected $appends = ['is_superadmin', 'is_client', 'is_agency','is_admissionteam','is_frontoffice', ];

    public $table = 'walkinform';

    public static $searchable = [
        'name',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    protected $fillable = [
        'name',
        'email',
        'phone',
        'source_id'
    ];

    public function leads()
{
    return $this->hasMany(Lead::class);
}

public function sources()
{
    return $this->belongsto(Source::class);
}
public function projectLeads()
{
    return $this->hasMany(Lead::class, 'project_id', 'id');
}
}
