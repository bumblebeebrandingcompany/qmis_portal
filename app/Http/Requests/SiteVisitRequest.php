<?php

namespace App\Http\Requests;

use App\Models\Sitevisit;
use Illuminate\Foundation\Http\FormRequest;

use Gate;
use Illuminate\Foundation\Http\UpdateSiteVisitRequest;
use Illuminate\Http\Response;

class SiteVisitRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->user()->is_superadmin || auth()->user()->is_frontoffice || auth()->user()->is_client|| auth()->user()->is_agencyanalytics|| auth()->user()->is_admissionteam;
    }
    public function rules()
    {
        return [
            'date' => 'required|date',
            'time_slot' => 'required|string', // Validate time_slot
            'lead_id' => [
                'required',
                'integer',
            ],
            'notes' => 'nullable|string'
        ];
    }
}
