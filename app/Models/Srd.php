<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Srd extends Model
{
    use HasFactory;
    public $table = 'srds';

    protected $fillable = [
        'project_id',
        'campaign',
        'source'
    ];
    public function project()
    {
        return $this->belongsTo(Project::class );
    }
}
