<?php

namespace App\Models;

use App\Traits\Auditable;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Lead extends Model
{
    use Auditable, HasFactory;

    public $table = 'leads';

    protected $appends = ['lead_info'];

    public static $searchable = [
        'email',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public const DEFAULT_WEBHOOK_FIELDS = [
        'name',
        'email',
        'phone',
        'predefined_comments',
        'predefined_cp_comments',
        'predefined_created_by',
        'predefined_created_at',
        'predefined_source_name',
        'predefined_campaign_name',
        'predefined_agency_name',
        'predefined_additional_email',
        'predefined_secondary_phone',
        'predefined_source_field1',
        'predefined_source_field2',
        'predefined_source_field3',
        'predefined_source_field4',
        'predefined_lead_ref_no'
    ];

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'lead_details' => 'array',
        'webhook_response' => 'array',
        'lead_event_webhook_response' => 'array',

    ];


    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function source()
    {
        return $this->belongsTo(Source::class, 'source_id');
    }

    public function campaign()
    {
        return $this->belongsTo(Campaign::class, 'campaign_id');
    }

    public function events()
    {
        return $this->hasMany(LeadEvents::class, 'lead_id');
    }

    public function parentStage()
    {
        return $this->belongsTo(ParentStage::class, 'parent_stage_id');
    }

    public function stage()
    {
        return $this->belongsTo(Stage::class, 'stage_id');
    }

    public function users()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function siteVisit()
    {
        return $this->belongsTo(SiteVisit::class, 'sitevisit_id');
    }

    // Define a method to get the parent_stage_name
    public function getParentStageNameAttribute()
    {
        return $this->stage->parentStage->name ?? null;
    }

    public function flattenData($datas)
    {
        $singleDimArr = [];
        foreach ($datas as $key => $data) {
            if (!empty($data) && !is_array($data))
                $singleDimArr[$key] = $data;
            if (!empty($data) && is_array($data)) {
                $singleDimArr = array_merge($singleDimArr, $this->flattenData($data));
            }
        }
        return $singleDimArr;
    }

    /**
     * Get lead details by converting 2D[] => 1D[]
     */
    public function getLeadInfoAttribute()
    {
        $lead_info = [];
        $lead_details = $this->lead_details;

        if (empty($lead_details))
            return $lead_info;

        foreach ($lead_details as $key => $lead_detail) {
            if (!empty($lead_detail) && !is_array($lead_detail))
                $lead_info[$key] = $lead_detail;
            if (!empty($lead_detail) && is_array($lead_detail)) {
                $lead_info = array_merge($lead_info, $this->flattenData($lead_detail));
            }
        }

        return $lead_info;
    }

    public static function getStages()
    {

        $stages = Lead::whereNotNull('parent_stage_id')
            ->pluck('parent_stage_id', )
            ->toArray();
        $unique_stages = array_unique($stages);

        $card_classes = ['card-primary', 'card-danger', 'card-success', 'card-info', 'card-warning', 'card-secondary', 'card-dark'];
        $lead_stages = [];
        foreach ($unique_stages as $stage) {
            $parentStage = ParentStage::find($stage); // Adjust the model name accordingly
            $dataForStage = Lead::where('parent_stage_id', $stage)->get();
            $lead_stages[$stage] = [
                'class' => $card_classes[array_rand($card_classes)],
                'title' => $parentStage ? $parentStage->name : ucfirst(str_replace('_', ' ', $stage)),
                'data' => $dataForStage,
            ];
        }
        return $lead_stages;
    }

    public function logTimeline($description, $activityType = null, $activityId = null)
    {
        $data = [
            'description' => $description,
        ];

        if ($activityType !== null) {
            $data['activity_type'] = $activityType;
        }

        if ($activityId !== null) {
            $data['activity_id'] = $activityId;
        }

        $this->timeline()->create($data);
    }


    public function timeline()
    {
        return $this->hasMany(LeadTimeline::class, 'lead_id');
    }
}

