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

    protected $fillable = ['lead_id','note_text','stage_id']; // Specify the fillable attributes


    public function rules()
    {
        return [

            'note_text' => [
                'string',
                'nullable'

            ],
        ];
    }

    public function timeline()
{
    return $this->hasMany(LeadTimeline::class);
}
public function logTimeline($lead, $note, $activityType, $description)
{
    $timeline = new LeadTimeline();
    $timeline->lead_id = $lead->id;
    $timeline->activity_type = $activityType;
    $payload = [
        'lead' => $lead->toArray(),
        'notes' => $note->toArray()
    ];
    $timeline->payload = json_encode($payload); // Convert array to JSON
    $timeline->description = $description;
    $timeline->created_at = now();
    $timeline->save();
}


}
