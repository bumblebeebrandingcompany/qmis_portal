<?php
namespace App\Models;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    public $table = 'notes';

    protected $dates = [
        'id',
        'note_text',
        'created_at',
    ];
    protected $fillable = ['id','note_text','created_at',]; // Specify the fillable attributes
}
