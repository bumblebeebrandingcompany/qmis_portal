<?php

namespace App\Http\Requests;

use App\Models\Followup;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreFollowupRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->user()->is_superadmin || auth()->user()->is_admissionteam || auth()->user()->is_presales|| auth()->user()->is_front_office;
    }

    public function rules()
    {
        return [
            'lead_id' => 'required|exists:leads,id',
            'followup_date' => 'required|date',
            'followup_time' => 'required',
            'notes' => 'required|string|max:500',
            'parent_stage_id' => 'required|integer', // Include this if `stage_id` is required
        ];
    }
}
