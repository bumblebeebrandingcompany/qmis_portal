<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Url extends Model
{
    use HasFactory;
    public $table = 'urls';

    protected $fillable = [
        'project_id',
        'source_name',
        'campaign_name',
        'sub_source_name',
        'webhook_secret',
        'mapped_fields',
    ];

    // protected $casts = [
    //     'mapped_fields' => 'array',
    // ];

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }
}
