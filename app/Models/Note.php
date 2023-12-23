<?php
namespace App\Models;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    public $table = 'notes';

    protected $dates = [
        'created_at',
    ];

    protected $fillable = ['note_text','lead_id']; // Specify the fillable attributes


    public function logTimeline($lead,$description, $activityType = null)
    {
        $data = [
            'lead_id' => $lead,
            'description' => $description,
        ];

        if ($activityType !== null) {
            $data['activity_type'] = $activityType;
        }



        $this->timeline()->create($data);
    }

    public function timeline()
{
    return $this->hasMany(LeadTimeline::class);
}
}
