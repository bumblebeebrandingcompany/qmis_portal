<?php

namespace App\Http\Controllers\Admin;

use App\Models\Lead;
use App\Models\User;
use LaravelDaily\LaravelCharts\Classes\LaravelChart;
use App\Utils\Util;
use Illuminate\Http\Request;

class ReportController
{
    /**
    * All Utils instance.
    *
    */
    protected $util;

    /**
    * Constructor
    *
    */
    public function __construct(Util $util)
    {
        $this->util = $util;
    }
    public function index()
    {
        if(auth()->user()->is_channel_partner || auth()->user()->is_channel_partner_manager) {
            abort(403, 'Unauthorized.');
        }

        $__global_clients_filter = $this->util->getGlobalClientsFilter();
        if(!empty($__global_clients_filter)) {
            $project_ids = $this->util->getClientsProjects($__global_clients_filter);
            $campaign_ids = $this->util->getClientsCampaigns($__global_clients_filter);
        } else {
            $project_ids = $this->util->getUserProjects(auth()->user());
            $campaign_ids = $this->util->getCampaigns(auth()->user(), $project_ids);
        }

        $settings1 = [
            'chart_title'           => 'Current Applications',
            'chart_type'            => 'number_block',
            'report_type'           => 'group_by_date',
            'model'                 => 'App\Models\Lead',
            'group_by_field'        => 'created_at', // Assuming 'created_at' is the timestamp field
            'group_by_period'       => 'day',
            'aggregate_function'    => 'count',
            'filter_field'          => 'created_at',
            'group_by_field_format' => 'd-m-Y H:i:s',
            'column_class'          => 'col-md-4',
            'entries_number'        => '5',
            'translation_key'       => 'user',
        ];

        $settings1['total_number'] = 0;
        if (class_exists($settings1['model'])) {
            $settings1['total_number'] = $settings1['model']::when(isset($settings1['filter_field']), function ($query) use ($settings1) {
                if (isset($settings1['filter_days'])) {
                    return $query->where($settings1['filter_field'], '>=',
                        now()->subDays($settings1['filter_days'])->format('Y-m-d'));
                } elseif (isset($settings1['filter_period'])) {
                    switch ($settings1['filter_period']) {
                        case 'week': $start = date('Y-m-d', strtotime('last Monday'));
                            break;
                        case 'month': $start = date('Y-m') . '-01';
                            break;
                        case 'year': $start = date('Y') . '-01-01';
                            break;
                    }
                    if (isset($start)) {
                        return $query->where($settings1['filter_field'], '>=', $start);
                    }
                }
            })->where('stage_id',13)
            ->{$settings1['aggregate_function'] ?? 'count'}($settings1['aggregate_field'] ?? '*');
        }

          $settings2  = [
            'chart_title'           => 'Total Admission',
            'chart_type'            => 'number_block',
            'report_type'           => 'group_by_date',
            'model'                 => 'App\Models\Admission',
            'group_by_field'        => 'created_at', // Assuming 'created_at' is the timestamp field
            'group_by_period'       => 'day',
            'aggregate_function'    => 'count',
            'filter_field'          => 'created_at',
            'group_by_field_format' => 'd-m-Y H:i:s',
            'column_class'          => 'col-md-4',
            'entries_number'        => '5',
            'translation_key'       => 'user',
        ];

        $settings2['total_number'] = 0;
        if (class_exists($settings2['model'])) {
            $settings2['total_number'] = $settings2['model']::when(isset($settings1['filter_field']), function ($query) use ($settings2) {
                if (isset($settings2['filter_days'])) {
                    return $query->where($settings2['filter_field'], '>=',
                        now()->subDays($settings2['filter_days'])->format('Y-m-d'));
                } elseif (isset($settings2['filter_period'])) {
                    switch ($settings2['filter_period']) {
                        case 'week': $start = date('Y-m-d', strtotime('last Monday'));
                            break;
                        case 'month': $start = date('Y-m') . '-01';
                            break;
                        case 'year': $start = date('Y') . '-01-01';
                            break;
                    }
                    if (isset($start)) {
                        return $query->where($settings2['filter_field'], '>=', $start);
                    }
                }
            })->where('stage_id',14)
            ->{$settings2['aggregate_function'] ?? 'count'}($settings2['aggregate_field'] ?? '*');
        }
        $settings3 = [
            'chart_title'           => 'Total Leads',
            'chart_type'            => 'number_block',
            'report_type'           => 'group_by_date',
            'model'                 => 'App\Models\Lead',
            'group_by_field'        => 'email_verified_at',
            'group_by_period'       => 'day',
            'aggregate_function'    => 'count',
            'filter_field'          => 'created_at',
            'group_by_field_format' => 'd-m-Y H:i:s',
            'column_class'          => 'col-md-4',
            'entries_number'        => '5',
            'translation_key'       => 'user',
        ];

        $settings3['total_number'] = 0;
        if (class_exists($settings3['model'])) {
            $settings3['total_number'] = $settings3['model']::when(isset($settings3['filter_field']), function ($query) use ($settings3) {
                if (isset($settings3['filter_days'])) {
                    return $query->where($settings3['filter_field'], '>=',
                        now()->subDays($settings3['filter_days'])->format('Y-m-d'));
                } elseif (isset($settings3['filter_period'])) {
                    switch ($settings3['filter_period']) {
                        case 'week': $start = date('Y-m-d', strtotime('last Monday'));
                        break;
                        case 'month': $start = date('Y-m') . '-01';
                        break;
                        case 'year': $start = date('Y') . '-01-01';
                        break;
                    }
                    if (isset($start)) {
                        return $query->where($settings3['filter_field'], '>=', $start);
                    }
                }
            })
            ->{$settings3['aggregate_function'] ?? 'count'}($settings3['aggregate_field'] ?? '*');
        }

        $settings4 = [
            'chart_title'           => 'Withdrawn',
            'chart_type'            => 'number_block',
            'report_type'           => 'group_by_date',
            'model'                 => 'App\Models\Lead',
            'group_by_field'        => 'created_at',
            'group_by_period'       => 'day',
            'aggregate_function'    => 'count',
            'filter_field'          => 'created_at',
            'group_by_field_format' => 'd-m-Y H:i:s',
            'column_class'          => 'col-md-4',
            'entries_number'        => '5',
            'translation_key'       => 'client',
        ];

        $settings4['total_number'] = 0;
        if (class_exists($settings4['model'])) {
            $settings4['total_number'] = $settings4['model']::when(isset($settings4['filter_field']), function ($query) use ($settings4) {
                if (isset($settings4['filter_days'])) {
                    return $query->where($settings4['filter_field'], '>=',
                        now()->subDays($settings4['filter_days'])->format('Y-m-d'));
                } elseif (isset($settings4['filter_period'])) {
                    switch ($settings4['filter_period']) {
                        case 'week': $start = date('Y-m-d', strtotime('last Monday'));
                        break;
                        case 'month': $start = date('Y-m') . '-01';
                        break;
                        case 'year': $start = date('Y') . '-01-01';
                        break;
                    }
                    if (isset($start)) {
                        return $query->where($settings4['filter_field'], '>=', $start);
                    }
                }
            })->where('stage_id',29)
                ->{$settings4['aggregate_function'] ?? 'count'}($settings4['aggregate_field'] ?? '*');
        }

        $settings9 = [
            'chart_title'           => 'Conducted & App Not Purchased',
            'chart_type'            => 'number_block',
            'report_type'           => 'group_by_date',
            'model'                 => 'App\Models\Lead',
            'group_by_field'        => 'created_at',
            'group_by_period'       => 'day',
            'aggregate_function'    => 'count',
            'filter_field'          => 'created_at',
            'group_by_field_format' => 'd-m-Y H:i:s',
            'column_class'          => 'col-md-4',
            'entries_number'        => '5',
            'translation_key'       => 'client',
        ];

        $settings9['total_number'] = 0;
        if (class_exists($settings9['model'])) {
            $settings9['total_number'] = $settings9['model']::when(isset($settings9['filter_field']), function ($query) use ($settings9) {
                if (isset($settings9['filter_days'])) {
                    return $query->where($settings9['filter_field'], '>=',
                        now()->subDays($settings9['filter_days'])->format('Y-m-d'));
                } elseif (isset($settings9['filter_period'])) {
                    switch ($settings9['filter_period']) {
                        case 'week': $start = date('Y-m-d', strtotime('last Monday'));
                        break;
                        case 'month': $start = date('Y-m') . '-01';
                        break;
                        case 'year': $start = date('Y') . '-01-01';
                        break;
                    }
                    if (isset($start)) {
                        return $query->where($settings9['filter_field'], '>=', $start);
                    }
                }
            })->where('stage_id',11)
                ->{$settings9['aggregate_function'] ?? 'count'}($settings9['aggregate_field'] ?? '*');
        }
        $settings7 = [
            'chart_title'           => 'Exisiting Student',
            'chart_type'            => 'number_block',
            'report_type'           => 'group_by_date',
            'model'                 => 'App\Models\Lead',
            'group_by_field'        => 'created_at',
            'group_by_period'       => 'day',
            'aggregate_function'    => 'count',
            'filter_field'          => 'created_at',
            'group_by_field_format' => 'd-m-Y H:i:s',
            'column_class'          => 'col-md-4',
            'entries_number'        => '5',
            'translation_key'       => 'client',
        ];

        $settings7['total_number'] = 0;
        if (class_exists($settings7['model'])) {
            $settings7['total_number'] = $settings7['model']::when(isset($settings7['filter_field']), function ($query) use ($settings7) {
                if (isset($settings7['filter_days'])) {
                    return $query->where($settings7['filter_field'], '>=',
                        now()->subDays($settings7['filter_days'])->format('Y-m-d'));
                } elseif (isset($settings7['filter_period'])) {
                    switch ($settings7['filter_period']) {
                        case 'week': $start = date('Y-m-d', strtotime('last Monday'));
                        break;
                        case 'month': $start = date('Y-m') . '-01';
                        break;
                        case 'year': $start = date('Y') . '-01-01';
                        break;
                    }
                    if (isset($start)) {
                        return $query->where($settings7['filter_field'], '>=', $start);
                    }
                }
            })->where('sub_source_id',18)
                ->{$settings7['aggregate_function'] ?? 'count'}($settings7['aggregate_field'] ?? '*');
        }
        $settings8 = [
            'chart_title'           => 'Total Applications Purchased',
            'chart_type'            => 'number_block',
            'report_type'           => 'group_by_date',
            'model'                 => 'App\Models\Application',
            'group_by_field'        => 'created_at',
            'group_by_period'       => 'day',
            'aggregate_function'    => 'count',
            'filter_field'          => 'created_at',
            'group_by_field_format' => 'd-m-Y H:i:s',
            'column_class'          => 'col-md-4',
            'entries_number'        => '5',
            'translation_key'       => 'client',
        ];
        $settings8['total_number'] = 0;
        if (class_exists($settings8['model'])) {
            $settings8['total_number'] = $settings8['model']::when(isset($settings8['filter_field']), function ($query) use ($settings8) {
                if (isset($settings8['filter_days'])) {
                    return $query->where($settings8['filter_field'], '>=',
                        now()->subDays($settings8['filter_days'])->format('Y-m-d'));
                } elseif (isset($settings8['filter_period'])) {
                    switch ($settings8['filter_period']) {
                        case 'week': $start = date('Y-m-d', strtotime('last Monday'));
                        break;
                        case 'month': $start = date('Y-m') . '-01';
                        break;
                        case 'year': $start = date('Y') . '-01-01';
                        break;
                    }
                    if (isset($start)) {
                        return $query->where($settings8['filter_field'], '>=', $start);
                    }
                }
            })
                ->{$settings8['aggregate_function'] ?? 'count'}($settings8['aggregate_field'] ?? '*');
        }
        $settings5 = [
            'chart_title'           => 'BBC leads',
            'chart_type'            => 'number_block',
            'report_type'           => 'group_by_date',
            'model'                 => 'App\Models\Lead',
            'group_by_field'        => 'created_at',
            'group_by_period'       => 'day',
            'aggregate_function'    => 'count',
            'filter_field'          => 'created_at',
            'group_by_field_format' => 'd-m-Y H:i:s',
            'column_class'          => 'col-md-4',
            'entries_number'        => '5',
            'translation_key'       => 'agency',
        ];
        $settings5['total_number'] = 0;
        if (class_exists($settings5['model'])) {
            $settings5['total_number'] = $settings5['model']::when(isset($settings5['filter_field']), function ($query) use ($settings5) {
                if (isset($settings5['filter_days'])) {
                    return $query->where($settings5['filter_field'], '>=',
                        now()->subDays($settings5['filter_days'])->format('Y-m-d'));
                } elseif (isset($settings5['filter_period'])) {
                    switch ($settings5['filter_period']) {
                        case 'week': $start = date('Y-m-d', strtotime('last Monday'));
                        break;
                        case 'month': $start = date('Y-m') . '-01';
                        break;
                        case 'year': $start = date('Y') . '-01-01';
                        break;
                    }
                    if (isset($start)) {
                        return $query->where($settings5['filter_field'], '>=', $start);
                    }
                }
            })->join('sub_source', 'leads.sub_source_id', '=', 'sub_source.id') // Adjust the join condition based on your actual relationship
      ->where('sub_source.campaign_id', 2) // Assuming 'campaign_id' is the correct field name in 'subsource' table
      ->where('leads.stage_id', '!=', 20)
      ->{$settings5['aggregate_function'] ?? 'count'}($settings5['aggregate_field'] ?? '*');
}
$settings6 = [
    'chart_title'           => 'Qmis Direct',
    'chart_type'            => 'number_block',
    'report_type'           => 'group_by_date',
    'model'                 => 'App\Models\Lead',
    'group_by_field'        => 'created_at',
    'group_by_period'       => 'day',
    'aggregate_function'    => 'count',
    'filter_field'          => 'created_at',
    'group_by_field_format' => 'd-m-Y H:i:s',
    'column_class'          => 'col-md-4',
    'entries_number'        => '5',
    'translation_key'       => 'agency',
];
$settings6['total_number'] = 0;
if (class_exists($settings6['model'])) {
    $settings6['total_number'] = $settings6['model']::when(isset($settings6['filter_field']), function ($query) use ($settings6) {
        if (isset($settings6['filter_days'])) {
            return $query->where($settings6['filter_field'], '>=',
                now()->subDays($settings6['filter_days'])->format('Y-m-d'));
        } elseif (isset($settings6['filter_period'])) {
            switch ($settings6['filter_period']) {
                case 'week': $start = date('Y-m-d', strtotime('last Monday'));
                break;
                case 'month': $start = date('Y-m') . '-01';
                break;
                case 'year': $start = date('Y') . '-01-01';
                break;
            }
            if (isset($start)) {
                return $query->where($settings6['filter_field'], '>=', $start);
            }
        }

    })->join('sub_source', 'leads.sub_source_id', '=', 'sub_source.id') // Adjust the join condition based on your actual relationship
->where('sub_source.campaign_id', 3) // Assuming 'campaign_id' is the correct field name in 'subsource' table
->{$settings6['aggregate_function'] ?? 'count'}($settings6['aggregate_field'] ?? '*');
}


$settings10 = [
    'chart_title'           => 'Qmis Direct',
    'chart_type'            => 'number_block',
    'report_type'           => 'group_by_date',
    'model'                 => 'App\Models\Application',
    'group_by_field'        => 'created_at',
    'group_by_period'       => 'day',
    'aggregate_function'    => 'count',
    'filter_field'          => 'created_at',
    'group_by_field_format' => 'd-m-Y H:i:s',
    'column_class'          => 'col-md-4',
    'entries_number'        => '5',
    'translation_key'       => 'agency',
];
$settings10['total_number'] = 0;
if (class_exists($settings10['model'])) {
    $settings10['total_number'] = $settings10['model']::when(isset($settings10['filter_field']), function ($query) use ($settings10) {
        if (isset($settings10['filter_days'])) {
            return $query->where($settings10['filter_field'], '>=',
                now()->subDays($settings10['filter_days'])->format('Y-m-d'));
        } elseif (isset($settings10['filter_period'])) {
            switch ($settings10['filter_period']) {
                case 'week': $start = date('Y-m-d', strtotime('last Monday'));
                break;
                case 'month': $start = date('Y-m') . '-01';
                break;
                case 'year': $start = date('Y') . '-01-01';
                break;
            }
            if (isset($start)) {
                return $query->where($settings10['filter_field'], '>=', $start);
            }
        }

    })->join('leads', 'applications.lead_id', '=', 'leads.id')
    ->join('sub_source', 'leads.sub_source_id', '=', 'sub_source.id')
    ->where('stage_id',13)
    ->where('sub_source.campaign_id', 2) // Assuming 'campaign_id' is the correct field name in 'subsource' table
->{$settings10['aggregate_function'] ?? 'count'}($settings10['aggregate_field'] ?? '*');
}
$settings11 = [
    'chart_title'           => 'Qmis Direct',
    'chart_type'            => 'number_block',
    'report_type'           => 'group_by_date',
    'model'                 => 'App\Models\Application',
    'group_by_field'        => 'created_at',
    'group_by_period'       => 'day',
    'aggregate_function'    => 'count',
    'filter_field'          => 'created_at',
    'group_by_field_format' => 'd-m-Y H:i:s',
    'column_class'          => 'col-md-4',
    'entries_number'        => '5',
    'translation_key'       => 'agency',
];
$settings11['total_number'] = 0;
if (class_exists($settings11['model'])) {
    $settings11['total_number'] = $settings11['model']::when(isset($settings11['filter_field']), function ($query) use ($settings11) {
        if (isset($settings11['filter_days'])) {
            return $query->where($settings11['filter_field'], '>=',
                now()->subDays($settings11['filter_days'])->format('Y-m-d'));
        } elseif (isset($settings11['filter_period'])) {
            switch ($settings11['filter_period']) {
                case 'week': $start = date('Y-m-d', strtotime('last Monday'));
                break;
                case 'month': $start = date('Y-m') . '-01';
                break;
                case 'year': $start = date('Y') . '-01-01';
                break;
            }
            if (isset($start)) {
                return $query->where($settings11['filter_field'], '>=', $start);
            }
        }

    })->join('leads', 'applications.lead_id', '=', 'leads.id')
    ->join('sub_source', 'leads.sub_source_id', '=', 'sub_source.id')
    ->where('sub_source.campaign_id', 3) // Assuming 'campaign_id' is the correct field name in 'subsource' table
->{$settings11['aggregate_function'] ?? 'count'}($settings11['aggregate_field'] ?? '*');
}
$settings12  = [
    'chart_title'           => 'Admission',
    'chart_type'            => 'number_block',
    'report_type'           => 'group_by_date',
    'model'                 => 'App\Models\Lead',
    'group_by_field'        => 'created_at', // Assuming 'created_at' is the timestamp field
    'group_by_period'       => 'day',
    'aggregate_function'    => 'count',
    'filter_field'          => 'created_at',
    'group_by_field_format' => 'd-m-Y H:i:s',
    'column_class'          => 'col-md-4',
    'entries_number'        => '5',
    'translation_key'       => 'user',
];

$settings12['total_number'] = 0;
if (class_exists($settings12['model'])) {
    $settings12['total_number'] = $settings12['model']::when(isset($settings1['filter_field']), function ($query) use ($settings12) {
        if (isset($settings12['filter_days'])) {
            return $query->where($settings12['filter_field'], '>=',
                now()->subDays($settings12['filter_days'])->format('Y-m-d'));
        } elseif (isset($settings12['filter_period'])) {
            switch ($settings12['filter_period']) {
                case 'week': $start = date('Y-m-d', strtotime('last Monday'));
                    break;
                case 'month': $start = date('Y-m') . '-01';
                    break;
                case 'year': $start = date('Y') . '-01-01';
                    break;
            }
            if (isset($start)) {
                return $query->where($settings12['filter_field'], '>=', $start);
            }
        }
    })->where('stage_id',14)
    ->join('sub_source', 'leads.sub_source_id', '=', 'sub_source.id') // Adjust the join condition based on your actual relationship
    ->where('sub_source.campaign_id', 2)
    ->{$settings12['aggregate_function'] ?? 'count'}($settings12['aggregate_field'] ?? '*');
}
$settings13  = [
    'chart_title'           => 'Admission',
    'chart_type'            => 'number_block',
    'report_type'           => 'group_by_date',
    'model'                 => 'App\Models\Lead',
    'group_by_field'        => 'created_at', // Assuming 'created_at' is the timestamp field
    'group_by_period'       => 'day',
    'aggregate_function'    => 'count',
    'filter_field'          => 'created_at',
    'group_by_field_format' => 'd-m-Y H:i:s',
    'column_class'          => 'col-md-4',
    'entries_number'        => '5',
    'translation_key'       => 'user',
];
$settings13['total_number'] = 0;
if (class_exists($settings13['model'])) {
    $settings13['total_number'] = $settings13['model']::when(isset($settings1['filter_field']), function ($query) use ($settings13) {
        if (isset($settings13['filter_days'])) {
            return $query->where($settings13['filter_field'], '>=',
                now()->subDays($settings13['filter_days'])->format('Y-m-d'));
        } elseif (isset($settings13['filter_period'])) {
            switch ($settings13['filter_period']) {
                case 'week': $start = date('Y-m-d', strtotime('last Monday'));
                    break;
                case 'month': $start = date('Y-m') . '-01';
                    break;
                case 'year': $start = date('Y') . '-01-01';
                    break;
            }
            if (isset($start)) {
                return $query->where($settings13['filter_field'], '>=', $start);
            }
        }
    })->where('stage_id',14)
    ->join('sub_source', 'leads.sub_source_id', '=', 'sub_source.id') // Adjust the join condition based on your actual relationship
    ->where('sub_source.campaign_id', 3)
    ->{$settings13['aggregate_function'] ?? 'count'}($settings13['aggregate_field'] ?? '*');
}
$settings14  = [
    'chart_title'           => 'Admission',
    'chart_type'            => 'number_block',
    'report_type'           => 'group_by_date',
    'model'                 => 'App\Models\Lead',
    'group_by_field'        => 'created_at', // Assuming 'created_at' is the timestamp field
    'group_by_period'       => 'day',
    'aggregate_function'    => 'count',
    'filter_field'          => 'created_at',
    'group_by_field_format' => 'd-m-Y H:i:s',
    'column_class'          => 'col-md-4',
    'entries_number'        => '5',
    'translation_key'       => 'user',
];
$settings14['total_number'] = 0;
if (class_exists($settings14['model'])) {
    $settings14['total_number'] = $settings14['model']::when(isset($settings1['filter_field']), function ($query) use ($settings14) {
        if (isset($settings14['filter_days'])) {
            return $query->where($settings14['filter_field'], '>=',
                now()->subDays($settings14['filter_days'])->format('Y-m-d'));
        } elseif (isset($settings14['filter_period'])) {
            switch ($settings14['filter_period']) {
                case 'week': $start = date('Y-m-d', strtotime('last Monday'));
                    break;
                case 'month': $start = date('Y-m') . '-01';
                    break;
                case 'year': $start = date('Y') . '-01-01';
                    break;
            }
            if (isset($start)) {
                return $query->where($settings14['filter_field'], '>=', $start);
            }
        }
    })->where('stage_id',29)
    ->join('sub_source', 'leads.sub_source_id', '=', 'sub_source.id') // Adjust the join condition based on your actual relationship
    ->where('sub_source.campaign_id', 2)
    ->{$settings14['aggregate_function'] ?? 'count'}($settings14['aggregate_field'] ?? '*');
}
$settings15  = [
    'chart_title'           => 'Admission',
    'chart_type'            => 'number_block',
    'report_type'           => 'group_by_date',
    'model'                 => 'App\Models\Lead',
    'group_by_field'        => 'created_at', // Assuming 'created_at' is the timestamp field
    'group_by_period'       => 'day',
    'aggregate_function'    => 'count',
    'filter_field'          => 'created_at',
    'group_by_field_format' => 'd-m-Y H:i:s',
    'column_class'          => 'col-md-4',
    'entries_number'        => '5',
    'translation_key'       => 'user',
];
$settings15['total_number'] = 0;
if (class_exists($settings15['model'])) {
    $settings15['total_number'] = $settings15['model']::when(isset($settings1['filter_field']), function ($query) use ($settings15) {
        if (isset($settings15['filter_days'])) {
            return $query->where($settings15['filter_field'], '>=',
                now()->subDays($settings15['filter_days'])->format('Y-m-d'));
        } elseif (isset($settings15['filter_period'])) {
            switch ($settings15['filter_period']) {
                case 'week': $start = date('Y-m-d', strtotime('last Monday'));
                    break;
                case 'month': $start = date('Y-m') . '-01';
                    break;
                case 'year': $start = date('Y') . '-01-01';
                    break;
            }
            if (isset($start)) {
                return $query->where($settings15['filter_field'], '>=', $start);
            }
        }
    })->where('stage_id',29)
    ->join('sub_source', 'leads.sub_source_id', '=', 'sub_source.id') // Adjust the join condition based on your actual relationship
    ->where('sub_source.campaign_id', 3)
    ->{$settings1['aggregate_function'] ?? 'count'}($settings15['aggregate_field'] ?? '*');
}
function generateChartSettings($title, $campaignId, $filterDays = null, $filterPeriod = null)
{
    $settings = [
        'chart_title'           => $title,
        'chart_type'            => 'number_block',
        'report_type'           => 'group_by_date',
        'model'                 => 'App\Models\Lead',
        'group_by_field'        => 'created_at',
        'group_by_period'       => 'day',
        'aggregate_function'    => 'count',
        'filter_field'          => 'created_at',
        'group_by_field_format' => 'd-m-Y H:i:s',
        'column_class'          => 'col-md-4',
        'entries_number'        => '5',
        'translation_key'       => 'agency',
    ];

    $settings['total_number'] = 0;

    if (class_exists($settings['model'])) {
        $settings['total_number'] = $settings['model']::when(isset($settings['filter_field']), function ($query) use ($settings, $filterDays, $filterPeriod) {
            if ($filterDays) {
                return $query->where($settings['filter_field'], '>=', now()->subDays($filterDays)->format('Y-m-d'));
            } elseif ($filterPeriod) {
                switch ($filterPeriod) {
                    case 'week':
                        $start = date('Y-m-d', strtotime('last Monday'));
                        break;
                    case 'month':
                        $start = date('Y-m') . '-01';
                        break;
                    case 'year':
                        $start = date('Y') . '-01-01';
                        break;
                }
                if (isset($start)) {
                    return $query->where($settings['filter_field'], '>=', $start);
                }
            }
        })->join('sub_source', 'leads.sub_source_id', '=', 'sub_source.id')
            ->where('sub_source.campaign_id', $campaignId)
            ->{$settings['aggregate_function'] ?? 'count'}($settings['aggregate_field'] ?? '*');
    }

    return $settings;
}

$settings16  = [
    'chart_title'           => 'Cancelled',
    'chart_type'            => 'number_block',
    'report_type'           => 'group_by_date',
    'model'                 => 'App\Models\Lead',
    'group_by_field'        => 'created_at', // Assuming 'created_at' is the timestamp field
    'group_by_period'       => 'day',
    'aggregate_function'    => 'count',
    'filter_field'          => 'created_at',
    'group_by_field_format' => 'd-m-Y H:i:s',
    'column_class'          => 'col-md-4',
    'entries_number'        => '5',
    'translation_key'       => 'user',
];

$settings16['total_number'] = 0;
if (class_exists($settings16['model'])) {
    $settings16['total_number'] = $settings16['model']::when(isset($settings1['filter_field']), function ($query) use ($settings16) {
        if (isset($settings16['filter_days'])) {
            return $query->where($settings16['filter_field'], '>=',
                now()->subDays($settings16['filter_days'])->format('Y-m-d'));
        } elseif (isset($settings16['filter_period'])) {
            switch ($settings16['filter_period']) {
                case 'week': $start = date('Y-m-d', strtotime('last Monday'));
                    break;
                case 'month': $start = date('Y-m') . '-01';
                    break;
                case 'year': $start = date('Y') . '-01-01';
                    break;
            }
            if (isset($start)) {
                return $query->where($settings16['filter_field'], '>=', $start);
            }
        }
    })->where('stage_id',20)
    ->{$settings16['aggregate_function'] ?? 'count'}($settings16['aggregate_field'] ?? '*');
}
$settings17  = [
    'chart_title'           => 'Cancelled',
    'chart_type'            => 'number_block',
    'report_type'           => 'group_by_date',
    'model'                 => 'App\Models\Admission',
    'group_by_field'        => 'created_at', // Assuming 'created_at' is the timestamp field
    'group_by_period'       => 'day',
    'aggregate_function'    => 'count',
    'filter_field'          => 'created_at',
    'group_by_field_format' => 'd-m-Y H:i:s',
    'column_class'          => 'col-md-4',
    'entries_number'        => '5',
    'translation_key'       => 'user',
];

$settings17['total_number'] = 0;
if (class_exists($settings17['model'])) {
    $settings17['total_number'] = $settings17['model']::when(isset($settings1['filter_field']), function ($query) use ($settings17) {
        if (isset($settings17['filter_days'])) {
            return $query->where($settings17['filter_field'], '>=',
                now()->subDays($settings17['filter_days'])->format('Y-m-d'));
        } elseif (isset($settings17['filter_period'])) {
            switch ($settings17['filter_period']) {
                case 'week': $start = date('Y-m-d', strtotime('last Monday'));
                    break;
                case 'month': $start = date('Y-m') . '-01';
                    break;
                case 'year': $start = date('Y') . '-01-01';
                    break;
            }
            if (isset($start)) {
                return $query->where($settings17['filter_field'], '>=', $start);
            }
        }
    })->where('stage_id',14)
    ->{$settings17['aggregate_function'] ?? 'count'}($settings17['aggregate_field'] ?? '*');
}
// Retrieve leads with their subsources and group them by campaign_id
$leadsByCampaign = Lead::with('subsources')
    ->get()
    ->groupBy(function ($lead) {
        return optional($lead->subsources)->campaign_id;
    });
$campaignLeads = [];
$campaignLabels = [];
// Iterate over leads grouped by campaign
foreach ($leadsByCampaign as $campaignId => $leads) {
    // Calculate the lead count for the campaign
    $leadCount = count($leads);
    // Extract the campaign name, or use 'Unknown' if not available
    $campaignName = optional(optional($leads->first()->subsources)->campaign)->name;

    $label = ($campaignName ?: 'Unknown') . ' (' . $leadCount . ')';
    $campaignLeads[] = $leadCount;
    $campaignLabels[] = $label;
}
$funnelSettings = [
    [
        'title' => 'Total Leads',
        'aggregate_function' => 'count',
        'sub_source_id' => 18,
        'sub_source_id_operator' => '!=',
        'model' => 'App\Models\Lead'
    ],
    [
        'title' => 'Conducted',
        'stage_id' => 20,
        'stage_id_operator' => '!=',
        'sub_source_id' => 18,
        'sub_source_id_operator' => '!=',
        'model' => 'App\Models\Lead'
    ],

    [
        'title' => 'App Purchased',
        'stage_id' => 13,
        'model' => 'App\Models\Application'
    ],
    [
        'title' => 'Admission',
        'stage_id' => 14,
        'model' => 'App\Models\Admission'
    ],
    [
        'title' => 'Cancelled',
        'stage_id' => 20,
        'model' => 'App\Models\Lead'
    ],
    [
        'title' => 'Withdrawn',
        'stage_id' => 29,
        'model' => 'App\Models\Lead'
    ],
];

function fetchDataForFunnel($settings) {
    $query = $settings['model']::query();

    // Apply stage_id filter if provided
    if (isset($settings['stage_id'])) {
        // Check if stage_id operator is provided
        if (isset($settings['stage_id_operator']) && $settings['stage_id_operator'] == '!=') {
            $query->where('stage_id', '!=', $settings['stage_id']);
        } else {
            $query->where('stage_id', $settings['stage_id']);
        }
    }

    // Apply sub_source_id filter if provided
    if (isset($settings['sub_source_id'])) {
        // Check if sub_source_id operator is provided
        if (isset($settings['sub_source_id_operator']) && $settings['sub_source_id_operator'] == '!=') {
            $query->where('sub_source_id', '!=', $settings['sub_source_id']);
        } else {
            $query->where('sub_source_id', $settings['sub_source_id']);
        }
    }

    // Get count
    return $query->count();
}
$totalLeads = fetchDataForFunnel($funnelSettings[0]);
$dataPoints = [];
// Calculate percentages relative to the Total Leads and include count
foreach ($funnelSettings as $settings) {
    $count = fetchDataForFunnel($settings);
    $percentage = $totalLeads > 0 ? ($count / $totalLeads) * 100 : 0;
    $dataPoints[] = ['label' => $settings['title'], 'y' => $percentage, 'count' => $count];
}
$leads=Lead::all();
$users = User::all();

        return view('admin.report.index', compact('settings1', 'settings2', 'settings3', 'settings4','settings5','settings6','settings7','settings8','settings9','settings10','settings11','settings12','campaignLeads','campaignLabels','settings13','settings14','settings15','settings16','settings17','leads','users','leadCount','dataPoints'));}
        public function storeGlobalClientFilters(Request $request)
    {
        if($request->ajax()) {
            $client_ids = $request->input('client_ids');
            session(['__global_clients_filter' => $client_ids]);
            return [
                'success' => true,
                'msg' => __('messages.success')
            ];
        }
    }
}





