<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lost extends Model
{
    use HasFactory;


    public $table = 'lost';

    protected $dates = [
        'created_at',
        'updated_at',

    ];
    protected $fillable = [
        'notes','reason','lead_id','tag_id'
      ];
    public function users()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    public function lead()
    {
        return $this->belongsTo(Lead::class);
    }
}
