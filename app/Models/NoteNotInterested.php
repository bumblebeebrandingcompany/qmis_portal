<?php

// app/Models/ParentStage.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NoteNotInterested extends Model
{
    use HasFactory;
    public $table = 'note_not_interested';

    protected $fillable = ['notes'];


}
