<?php

namespace App\Http\Requests;

use App\Models\CallRecord;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class CallRecordRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }
    public function rules()
    {
        return [
            'called_by' => 'required|string',
            'called_on' => 'required|string',
            'call_start_time' => 'required|date', // Assuming it's a date field
            'call_duration' => 'required|integer',
            'call_recordings' => 'required|string', // Assuming it's a string field
            'status' => 'required|string', // Add a suitable validation rule for 'status'
        ];
    }
}

