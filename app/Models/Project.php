<?php

namespace App\Models;

use App\Traits\Auditable;
use Carbon\Carbon;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Project extends Model implements HasMedia
{
    use SoftDeletes, InteractsWithMedia, Auditable, HasFactory;

    public $table = 'projects';

    public static $searchable = [
        'name',
        'location',
    ];

    protected $dates = [
        // 'start_date',
        // 'end_date',
        'created_at',
        'updated_at',
        'deleted_at',
    ];



    protected $fillable = [
        'name',
        // 'start_date',
        // 'end_date',
        'created_by',
        'client_id',
        'location',
        'description',
        'webhook_fields',
        'outgoing_apis',
        'created_at',
        'updated_at',
        'deleted_at',
        // 'custom_fields',
        // 'essential_fields',
        // 'sales_fields',
        // 'system_fields'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'custom_fields' => 'array',
        'essential_fields'=>'array',
        'sales_fields'  => 'array',
        'system_fields' => 'array',
        'webhook_fields' => 'array',
        'outgoing_apis' => 'array',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }



    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')->fit('crop', 50, 50);
        $this->addMediaConversion('preview')->fit('crop', 120, 120);
    }

    public function projectLeads()
    {
        return $this->hasMany(Lead::class, 'project_id', 'id');
    }

    public function projectCampaigns()
    {
        return $this->hasMany(Campaign::class, 'project_id', 'id');
    }

    public function getStartDateAttribute($value)
    {
        return $value ? Carbon::parse($value)->format(config('panel.date_format')) : null;
    }

    public function setStartDateAttribute($value)
    {
        $this->attributes['start_date'] = $value ? Carbon::createFromFormat(config('panel.date_format'), $value)->format('Y-m-d') : null;
    }

    public function getEndDateAttribute($value)
    {
        return $value ? Carbon::parse($value)->format(config('panel.date_format')) : null;
    }

    public function setEndDateAttribute($value)
    {
        $this->attributes['end_date'] = $value ? Carbon::createFromFormat(config('panel.date_format'), $value)->format('Y-m-d') : null;
    }

    public function created_by()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function client()
    {
        return $this->belongsTo(Clients::class, 'client_id');
    }
}
