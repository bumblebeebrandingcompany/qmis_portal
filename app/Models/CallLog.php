<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CallLog extends Model
{
    protected $fillable = [
        'calldate',
        'source',
        'destination',
        'call_duration_with_ringing',
        'call_duration_talk_time',
        'call_disposition',
        'recording_url',
        'remote_id',
        'type'
    ];

    // Boot method to handle model events
    protected static function boot()
    {
        parent::boot();

        // Automatically generate a 10-digit numeric remote_id when creating a new CallLog
        static::creating(function ($model) {
            $model->remote_id = self::generateUniqueRemoteId();
        });
    }

    // Method to generate a unique 10-digit numeric remote_id
    protected static function generateUniqueRemoteId()
    {
        // Loop until a unique ID is generated
        while (true) {
            // Generate a random 10-digit number
            $remoteId = random_int(1000000000, 9999999999);

            // Check if this ID already exists in the database
            if (!self::where('remote_id', $remoteId)->exists()) {
                return $remoteId;
            }
        }
    }
}
