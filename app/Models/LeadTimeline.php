<?php

namespace App\Models;

// app/Models/LeadTimeline.php

use Illuminate\Database\Eloquent\Model;

class LeadTimeline extends Model
{


    protected $table = 'lead_timeline';

    protected $fillable = ['lead_id', 'description','activity_type','activity_id','site_visit_id','follow_up_id','note_id'];



    public function lead()
    {
        return $this->belongsTo(Lead::class);
    }

    public function note()
    {
        return $this->belongsTo(Note::class);
    }

    public function sitevisit()
    {
        return $this->belongsTo(SiteVisit::class);
    }

    public function followup()
    {
        return $this->belongsTo(Followup::class);
    }

    public function callrecord()
    {
        return $this->belongsTo(CallRecord::class);
    }


}
