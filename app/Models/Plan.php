<?php

// app/Models/Plan.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Package;

class Plan extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'duration',
    ];

    public function packages()
    {
        return $this->hasMany(Package::class);
    }
}
