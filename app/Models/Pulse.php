<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pulse extends Model
{
    public $table = 'pulse';

    protected $fillable = ['log'];
}

