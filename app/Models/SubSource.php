<?php

namespace App\Models;

use App\Traits\Auditable;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubSource extends Model
{


    public $table = 'sub_source';

    public static $searchable = [
        'name',
    ];

    protected $fillable = [
        'name',
        'project_id',
        'campaign_id',
        'source_id',
    ];


    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    public function campaign()
    {
        return $this->belongsTo(Campaign::class, 'campaign_id');
    }

    public function source()
    {
        return $this->belongsTo(Source::class, 'source_id');
    }
    public function walkin()
    {
        return $this->hasMany(Walkin::class, 'sub_source_id');
    }
    public function campaigns()
    {
        return $this->belongsTo(Campaign::class, 'campaign_id');
    }

}

