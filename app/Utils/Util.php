<?php

namespace App\Utils;

use App\Mail\SendOtpToMail;
use App\Models\Campaign;
use App\Models\EmailLog;
use App\Models\Project;
use App\Models\Lead;
use App\Models\LeadEvents;
use Illuminate\Support\Str;
use Spatie\WebhookServer\WebhookCall;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use App\Models\Source;
use Illuminate\Support\Facades\Log;
use App\Mail\ClientPortalLinkMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Http;

class Util
{
    public function getUserProjects($user)
    {
        $query = new Project();

        if (!($user->is_superadmin || $user->is_front_office)) {
            $cp_project_ids = $user->project_assigned ?? [];
            $query = $query->where(function ($q) use ($user, $cp_project_ids) {
                if ($user->is_channel_partner) {
                    $q->whereIn('id', $cp_project_ids);
                } else {
                    $q->where('created_by', $user->id)
                        ->orWhere('client_id', $user->client_id);
                }
            });
        }
        $project_ids = $query->pluck('id')->toArray();
        return $project_ids;
    }
    public function addCountryCode($phoneNumber)
    {
        if (!Str::startsWith($phoneNumber, '+')) {
            $phoneNumber = '+91' . ltrim($phoneNumber, '0');
        }

        Log::info('Modified phone number: ' . $phoneNumber);

        return $phoneNumber;
    }
    public function getCampaigns($user, $project_ids = [])
    {
        $query = new Campaign();

        if (!$user->is_superadmin && $user->is_agency) {
            $query = $query->where(function ($q) use ($user) {
                $q->where('agency_id', $user->agency_id);
            });
        }

        if (!$user->is_superadmin && $user->is_client) {
            $query = $query->where(function ($q) use ($project_ids) {
                $q->whereIn('project_id', $project_ids);
            });
        }

        $campaign_ids = $query->pluck('id')->toArray();

        return $campaign_ids;
    }

    public function getSource($user, $project_ids = [], $campaign_ids = [])
    {
        $query = new Source();

        if (!$user->is_superadmin && $user->is_agency) {
            $query = $query->where(function ($q) use ($user) {
                $q->where('agency_id', $user->agency_id);
            });
        }

        if (!$user->is_superadmin && $user->is_client) {
            $query = $query->where(function ($q) use ($project_ids) {
                $q->whereIn('project_id', $project_ids);
            });
        }

        if (!$user->is_superadmin && $user->is_client) {
            $query = $query->where(function ($q) use ($campaign_ids) {
                $q->whereIn('campaign_id', $campaign_ids);
            });
        }

        $source_ids = $query->pluck('id')->toArray();

        return $source_ids;
    }

    public function generateWebhookSecret()
    {
        $webhookSecret = (string) Str::uuid();
        return $webhookSecret;
    }


    public function sendClientPortalLink(Request $request, $leadId)
    {
        try {
            $lead = Lead::findOrFail($leadId);

            Mail::to($lead->email)->send(new ClientPortalLinkMail($lead));

            return response()->json(['message' => 'Client portal link sent to the lead\'s email.']);
        } catch (\Exception $e) {
            // Log the error message or handle it as needed
            \Log::error('Failed to send client portal link: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to send email.'], 500);
        }
    }

    public function sendOtpToEmail(Request $request, $leadId,$otp)
    {
        $validatedData = $request->validate([
            'father.email' => 'nullable|email',
            'mother.email' => 'nullable|email',
            'guardian.email' => 'nullable|email',
        ]);

        try {
            $lead = Lead::findOrFail($leadId);

            // Determine which email to use
            $emailToSendOtp = $validatedData['father']['email']
                ?? $validatedData['mother']['email']
                ?? $validatedData['guardian']['email'];

            if (!$emailToSendOtp) {
                return response()->json(['error' => 'No email address available for the lead.'], 400);
            }
            
            // $mailersend = new MailerSend(['api_key' => 'key']);
            $check_exist = EmailLog::where('email', $emailToSendOtp)->first();
            if ($check_exist) {
                Mail::to($emailToSendOtp)->send(new SendOtpToMail($lead, $otp));
            } else {
                $response = Http::post('https://api.mailersend.com/v1/email-verification/verify', [
                    'email' => $emailToSendOtp
                ]);
                if ($response->successful()) {
                    if ($response->json('status') === 'valid') {
                        $mail = new EmailLog();
                        $mail->lead_id = $lead->id;
                        $mail->error = '';
                        $mail->status = 1;
                        $mail->page = 'Otp Verification';
                        $mail->email = $emailToSendOtp;
                        $mail->save();
                        Mail::to($emailToSendOtp)->send(new SendOtpToMail($lead, $otp));
                    } else {
                        $mail = new EmailLog();
                        $mail->lead_id = $lead->id;
                        $mail->error = json_encode($response);
                        $mail->status = 0;
                        $mail->email = $emailToSendOtp;
                        $mail->page = 'Otp Verification';
                        $mail->save();
                    }
                } else {
                    $mail = new EmailLog();
                    $mail->lead_id = $lead->id;
                    $mail->error = json_encode($response);
                    $mail->status = 0;
                    $mail->email = $emailToSendOtp;
                    $mail->page = 'Otp Verification';
                    $mail->save();
                }
            }


            return response()->json(['message' => 'Client portal link sent to the lead\'s email.']);
        } catch (\Exception $e) {
            \Log::error('Failed to send client portal link: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to send email.'], 500);
        }
    }


    public function createLead($source, $payload)
    {
        $project = Project::where('id', $source->project_id)->first();

        $systemFields = [];

        foreach ($project->system_fields as $field) {
            $nestedKeys = explode('[', str_replace(']', '', $field['name_value']));
            $value = $payload;

            foreach ($nestedKeys as $nestedKey) {
                if (isset($value[$nestedKey])) {
                    $value = $value[$nestedKey];
                } else {
                    $value = null;
                    break;
                }
            }

            $systemFields[$field['name_value']] = $value ?? '';
        }

        // Display the contents of $systemFields
        // var_dump($systemFields);


        $essentialFields = [];

        foreach ($project->essential_fields as $field) {
            $nestedKeys = explode('[', str_replace(']', '', $field['name_value']));
            $value = $payload;

            foreach ($nestedKeys as $nestedKey) {
                if (isset($value[$nestedKey])) {
                    $value = $value[$nestedKey];
                } else {
                    $value = null;
                    break;
                }
            }

            $essentialFields[$field['name_value']] = $value ?? '';
        }
        $salesFields = [];

        foreach ($project->sales_fields as $field) {
            $nestedKeys = explode('[', str_replace(']', '', $field['name_value']));
            $value = $payload;

            foreach ($nestedKeys as $nestedKey) {
                if (isset($value[$nestedKey])) {
                    $value = $value[$nestedKey];
                } else {
                    $value = null;
                    break;
                }
            }

            $salesFields[$field['name_value']] = $value ?? '';
        }

        $customFields = [];

        foreach ($project->custom_fields as $field) {
            $nestedKeys = explode('[', str_replace(']', '', $field['name_value']));
            $value = $payload;

            foreach ($nestedKeys as $nestedKey) {
                if (isset($value[$nestedKey])) {
                    $value = $value[$nestedKey];
                } else {
                    $value = null;
                    break;
                }
            }

            $customFields[$field['name_value']] = $value ?? '';
        }

        // Define other lead attributes
        $name = !empty($source->name_key) ? ($payload[$source->name_key] ?? '') : ($payload['name'] ?? '');
        $email = !empty($source->email_key) ? ($payload[$source->email_key] ?? '') : ($payload['email'] ?? '');
        $additionalEmail = !empty($source->additional_email_key) ? ($payload[$source->additional_email_key] ?? '') : '';
        $phone = !empty($source->phone_key) ? ($payload[$source->phone_key] ?? '') : ($payload['phone'] ?? '');
        $secondaryPhone = !empty($source->secondary_phone_key) ? ($payload[$source->secondary_phone_key] ?? '') : '';

        // Convert $systemFields to a JSON string
        $systemFieldsString = json_encode($systemFields);
        $essentialFieldsString = json_encode($essentialFields);
        $salesFieldsString = json_encode($salesFields);
        $customFieldsString = json_encode($customFields);


        // Create the lead with all the attributes
        $lead = Lead::create([
            'source_id' => $source->id,
            'name' => $name ?? '',
            'email' => $email ?? '',
            'secondary_email' => $additionalEmail ?? '',
            'phone' => $phone ?? '',
            'secondary_phone' => $secondaryPhone ?? '',
            'project_id' => $source->project_id,
            'campaign_id' => $source->campaign_id,
            'lead_details' => $payload,
            'system_fields' => $systemFieldsString,
            'essential_fields' => $essentialFieldsString,
            'sales_fields' => $salesFieldsString,
            'custom_fields' => $customFieldsString,

        ]);
        $parentStageId = 8;

        $lead->ref_num = $this->generateLeadRefNum($lead);
        $lead->stage_id = 8;
        $lead->save();

        $this->storeUniqueWebhookFields($lead);

        $response = $this->sendApiWebhook($lead->id);

        return $response;
    }


    // public function sendWebhook($id)
    // {
    //     try {

    //         $lead = Lead::findOrFail($id);
    //         $source = Source::findOrFail($lead->source_id);

    //         if(
    //             !empty($source) &&
    //             !empty($source->outgoing_webhook) &&
    //             !empty($lead) &&
    //             !empty($lead->lead_details)
    //         ) {
    //             foreach ($source->outgoing_webhook as $webhook) {
    //                 if(!empty($webhook['url'])) {
    //                     if(!empty($webhook['secret_key'])) {
    //                         WebhookCall::create()
    //                             ->useSecret($webhook['secret_key'])
    //                             ->useHttpVerb($webhook['method'])
    //                             ->url($webhook['url'])
    //                             ->payload($lead->lead_details)
    //                             ->dispatch();
    //                     }

    //                     if(empty($webhook['secret_key'])) {
    //                         WebhookCall::create()
    //                             ->doNotSign()
    //                             ->useHttpVerb($webhook['method'])
    //                             ->url($webhook['url'])
    //                             ->payload($lead->lead_details)
    //                             ->dispatch();
    //                     }
    //                 }
    //             }
    //         }

    //         $output = ['success' => true, 'msg' => __('messages.success')];
    //     } catch (\Exception $e) {
    //         $output = ['success' => false, 'msg' => __('messages.something_went_wrong')];
    //     }
    //     return $output;
    // }

    public function sendApiWebhook($id)
    {
        $webhook_responses = [];
        $request_body = null;
        $lead = Lead::findOrFail($id);
        $project = Project::findOrFail($lead->project_id);

        //setting this to save sell do response only once in DB for a lead
        $is_sell_do_executed = false;

        try {

            if (
                !empty($project) &&
                !empty($project->outgoing_apis) &&
                !empty($lead)
            ) {
                $sell_do_response = !empty($lead->sell_do_response) ? json_decode($lead->sell_do_response, true) : [];
                foreach ($project->outgoing_apis as $api) {
                    $headers = !empty($api['headers']) ? json_decode($api['headers'], true) : [];
                    $request_body = $this->replaceTags($lead, $api);
                    if (!empty($api['url'])) {
                        $headers['secret-key'] = $api['secret_key'] ?? '';
                        $constants = $this->getApiConstants($api);
                        $request_body = array_merge($request_body, $constants);

                        //merge query parameter into request body
                        $queryString = parse_url($api['url'], PHP_URL_QUERY);
                        parse_str($queryString, $queryArray);
                        $request_body = array_merge($request_body, $queryArray);

                        $response = $this->postWebhook($api['url'], $api['method'], $headers, $request_body);

                        //checking this to save sell.do response only once in DB for a lead
                        // or update if any error
                        if (
                            (
                                !$is_sell_do_executed &&
                                empty($sell_do_response) &&
                                empty($lead->sell_do_lead_id)
                            ) ||
                            (
                                !$is_sell_do_executed &&
                                !empty($sell_do_response) &&
                                !empty($sell_do_response['error'])
                            )
                        ) {
                            if (strpos($api['url'], 'app.sell.do') !== false) {
                                if (!empty($response['sell_do_lead_id'])) {

                                    $lead->sell_do_is_exist = isset($response['selldo_lead_details']['lead_already_exists']) ? $response['selldo_lead_details']['lead_already_exists'] : false;

                                    $lead->sell_do_lead_created_at = isset($response['selldo_lead_details']['lead_created_at']) ? $response['selldo_lead_details']['lead_created_at'] : null;

                                    $lead->sell_do_lead_id = isset($response['sell_do_lead_id']) ? $response['sell_do_lead_id'] : null;

                                    $lead->sell_do_response = json_encode($response);

                                    $lead->sell_do_status = isset($response['selldo_lead_details']['status']) ? $response['selldo_lead_details']['status'] : null;

                                    $lead->sell_do_stage = isset($response['selldo_lead_details']['stage']) ? $response['selldo_lead_details']['stage'] : null;

                                    $lead->save();

                                }
                            }
                        }

                        $webhook_responses[] = [
                            'input' => $request_body,
                            'response' => $response
                        ];
                    }
                }
            }
            $output = ['success' => true, 'msg' => __('messages.success')];
        } catch (RequestException $e) {
            $webhook_responses[] = [
                'input' => $request_body,
                'response' => $e->getMessage()
            ];
            $output = ['success' => false, 'msg' => __('messages.something_went_wrong')];
        }

        /*
         * Save webhook responses
         */
        if (!empty($lead->webhook_response) && is_array($lead->webhook_response)) {
            $webhook_responses = array_merge($lead->webhook_response, $webhook_responses);
        }
        $lead->webhook_response = $webhook_responses;
        $lead->save();

        return $output;
    }

    public function replaceTags($lead, $api)
    {
        $request_body = $api['request_body'] ?? [];
        if (empty($request_body)) {
            return $lead->lead_details;
        }

        $tag_replaced_req_body = [];
        $source = $lead->source;
        foreach ($request_body as $value) {
            if (!empty($value['key']) && !empty($value['value'])) {
                if (count($value['value']) > 1) {
                    $arr_value = [];
                    foreach ($value['value'] as $field) {
                        if (isset($lead->lead_info[$field]) && !empty($lead->lead_info[$field])) {
                            $arr_value[] = $lead->lead_info[$field];
                        } else {
                            $arr_value[] = $this->getPredefinedValue($field, $lead, $source);
                        }
                    }
                    $empty_replaced_values = array_values(array_filter($arr_value));
                    $tag_replaced_req_body[$value['key']] = implode(' | ', $empty_replaced_values);
                } else {
                    $data_value = '';
                    if (
                        !empty($value['value']) &&
                        !empty($value['value'][0])
                    ) {
                        $data_value = $this->getPredefinedValue($value['value'][0], $lead, $source);
                    }
                    $tag_replaced_req_body[$value['key']] = $lead->lead_info[$value['value'][0]] ?? $data_value;
                }
            }
        }
        return $tag_replaced_req_body;
    }

    public function getPredefinedValue($field, $lead, $source = null)
    {
        if (
            (
                !empty($source->email_key) &&
                !empty($field) &&
                ($source->email_key == $field)
            ) ||
            (
                !empty($field) &&
                !empty($lead->email) &&
                in_array($field, ['email', 'Email', 'EMAIL'])
            )
        ) {
            return $lead->email ?? '';
        } else if (
            (
                !empty($source->phone_key) &&
                !empty($field) &&
                ($source->phone_key == $field)
            ) ||
            (
                !empty($field) &&
                !empty($lead->phone) &&
                in_array($field, ['phone', 'Phone', 'PHONE'])
            )
        ) {
            return $lead->phone ?? '';
        } else if (
            (
                !empty($source->name_key) &&
                !empty($field) &&
                ($source->name_key == $field)
            ) ||
            (
                !empty($field) &&
                !empty($lead->name) &&
                in_array($field, ['name', 'Name', 'NAME'])
            )
        ) {
            return $lead->name ?? '';
        } else if (
            !empty($field) &&
            in_array($field, ['predefined_comments'])
        ) {
            return $lead->comments ?? '';
        } else if (
            !empty($field) &&
            in_array($field, ['predefined_cp_comments'])
        ) {
            return $lead->cp_comments ?? '';
        } else if (
            !empty($field) &&
            in_array($field, ['predefined_created_by'])
        ) {
            return optional($lead->createdBy)->name ?? '';
        } else if (
            !empty($field) &&
            in_array($field, ['predefined_created_at'])
        ) {
            return $lead->created_at ?? '';
        } else if (
            !empty($field) &&
            in_array($field, ['predefined_name'])
        ) {
            if (!empty($lead->createdBy) && $lead->createdBy->user_type == 'ChannelPartner') {
                return 'Channel Partner';
            } else {
                return optional($lead->source)->name ?? '';
            }
        } else if (
            !empty($field) &&
            in_array($field, ['predefined_name'])
        ) {
            return optional($lead->campaign)->name ?? '';
        } else if (
            !empty($field) &&
            in_array($field, ['predefined_agency_name']) &&
            !empty($lead->campaign) &&
            !empty($lead->campaign->agency) &&
            !empty($lead->campaign->agency->name)

        ) {
            return $lead->campaign->agency->name ?? '';
        } else if (
            !empty($field) &&
            in_array($field, ['predefined_additional_email']) &&
            !empty($lead->secondary_email)
        ) {
            return $lead->secondary_email ?? '';
        } else if (
            !empty($field) &&
            in_array($field, ['predefined_secondary_phone']) &&
            !empty($lead->secondary_phone)
        ) {
            return $lead->secondary_phone ?? '';
        } else if (
            !empty($field) &&
            in_array($field, ['predefined_source_field1'])
        ) {
            return optional($lead->source)->source_field1 ?? '';
        } else if (
            !empty($field) &&
            in_array($field, ['predefined_source_field2'])
        ) {
            return optional($lead->source)->source_field2 ?? '';
        } else if (
            !empty($field) &&
            in_array($field, ['predefined_source_field3'])
        ) {
            return optional($lead->source)->source_field3 ?? '';
        } else if (
            !empty($field) &&
            in_array($field, ['predefined_source_field4'])
        ) {
            return optional($lead->source)->source_field4 ?? '';
        } else if (
            !empty($field) &&
            in_array($field, ['predefined_lead_ref_no'])
        ) {
            return $lead->ref_num ?? '';
        }
    }

    public function getLeadTags($id)
    {
        $lead = Lead::where('source_id', $id)
            ->latest()
            ->first();

        $tags = !empty($lead->lead_info) ? array_keys($lead->lead_info) : [];

        return $tags;
    }
    /*
     * return sources
     *
     * @param $for_cp: is channel partner
     *
     * @return array
     */
    public function getSources($for_cp = false)
    {
        $sources = Source::with(['project', 'campaign'])
            ->get();

        if ($for_cp) {
            $sources_arr = [];
            foreach ($sources as $source) {
                $sources_arr[$source->id] = $source->project->name . ' | ' . $source->campaign->name . ' | ' . $source->name;
            }
            return $sources_arr;
        }

        return $sources->pluck('name', 'id')->toArray();
    }
    //     public function getSourcesForProjectAndCampaign($project_id, $campaign_id)
// {
//     $sources = Source::whereHas('project', function ($query) use ($project_id) {
//             $query->where('id', $project_id);
//         })
//         ->whereHas('campaign', function ($query) use ($campaign_id) {
//             $query->where('id', $campaign_id);
//         })
//         ->get();

    //         return $sources->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
// }

    public function postWebhook($url, $method, $headers = [], $body = [])
    {
        if (in_array($method, ['get'])) {

            $client = new Client();
            $response = $client->get($url, [
                'query' => $body,
                'headers' => $headers,
            ]);

            return json_decode($response->getBody(), true);
        }
        if (in_array($method, ['post'])) {

            $client = new Client();
            $response = $client->post($url, [
                'headers' => $headers,
                'json' => $body,
            ]);

            return json_decode($response->getBody(), true);
        }
    }

    /*
     * return project dropdown
     *
     * @param $for_cp: is channel partner
     *
     * @return array
     */
    public function getProjectDropdown($for_cp = false)
    {
        $projects = Project::with(['client'])
            ->get();

        if ($for_cp) {
            $projects_arr = [];
            foreach ($projects as $project) {
                // $projects_arr[$project->id] = $project->client->name . ' | ' . $project->name;
            }
            return $projects_arr;
        }

        return $projects->pluck('name', 'id')->toArray();
    }

    public function getApiConstants($api)
    {
        if (isset($api['constants']) && !empty($api['constants'])) {
            $constants = [];
            foreach ($api['constants'] as $value) {
                if (!empty($value['key']) && !empty($value['value'])) {
                    $constants[$value['key']] = $value['value'];
                }
            }
            return $constants;
        }
        return [];
    }

    public function getGlobalClientsFilter()
    {
        $__global_clients_filter = session('__global_clients_filter');

        return $__global_clients_filter ?? [];
    }

    /*
     * return project ids for
     * clients
     *
     * @return array
     */
    public function getClientsProjects($client_ids = [])
    {
        $client_ids = empty($client_ids) ? $this->getGlobalClientsFilter() : $client_ids;
        if (empty($client_ids)) {
            return [];
        }
        $projects = Project::whereIn('client_id', $client_ids)
            ->pluck('id')->toArray();

        return $projects;
    }

    /*
     * return campaign ids for
     * clients
     *
     * @return array
     */
    public function getClientsCampaigns($client_ids = [])
    {
        $project_ids = $this->getClientsProjects($client_ids);

        if (empty($project_ids)) {
            return [];
        }

        $campaign_ids = Campaign::whereIn('project_id', $project_ids)
            ->pluck('id')->toArray();

        return $campaign_ids;
    }

    public function getClientsSources($client_ids = [])
    {
        $project_ids = $this->getClientsProjects($client_ids);
        $campaign_ids = $this->getClientsCampaigns($client_ids);


        if (empty($project_ids)) {
            return [];
        }

        $source_ids = Source::whereIn('project_id', $project_ids)
            ->orWhereIn('campaign_id', $campaign_ids)
            ->pluck('id')->toArray();

        return $source_ids;
    }

    public function getWebhookFieldsTags($id)
    {
        $project = Project::findOrFail($id);

        $db_fields = Lead::DEFAULT_WEBHOOK_FIELDS;
        $tags = !empty($project->webhook_fields) ? array_merge($project->webhook_fields, $db_fields) : $db_fields;

        return array_unique($tags);
    }

    public function storeUniqueWebhookFields($lead)
    {
        $project = Project::findOrFail($lead->project_id);

        $fields = !empty($lead->lead_info) ? array_keys($lead->lead_info) : [];
        $webhook_fields = !empty($project->webhook_fields) ? array_merge($project->webhook_fields, $fields) : $fields;
        $unique_webhook_fields = array_unique($webhook_fields);
        $project->webhook_fields = array_values($unique_webhook_fields);
        $project->save();
    }

    public function storeUniqueWebhookFieldsWhenCreatingWebhook($project)
    {
        $outgoing_apis = $project->outgoing_apis;
        $fields = [];

        foreach ($outgoing_apis as $outgoing_api) {
            if (isset($outgoing_api['request_body']['essential_fields']) && is_array($outgoing_api['request_body']['essential_fields'])) {
                $essential_fields = $outgoing_api['request_body']['essential_fields'];
                var_dump($essential_fields);

                foreach ($essential_fields['key'] as $index => $key) {

                    $value = $essential_fields['value'][$index];


                    if (isset($key, $value) && is_array($value)) {
                        $fields[] = [
                            'key' => $key,
                            'value' => $value
                        ];
                    }
                }
            }
        }

        dd($fields);

        $webhook_fields = !empty($project->webhook_fields) ? array_merge($project->webhook_fields, $fields) : $fields;
        $unique_webhook_fields = array_unique($webhook_fields, SORT_REGULAR);

        $project->webhook_fields = array_values($unique_webhook_fields);
        $project->save();


    }
    public function getClientProjects($id)
    {
        $projects = Project::where('client_id', $id)
            ->pluck('name', 'id')
            ->toArray();

        return $projects;
    }

    /**
     * generate lead ref no
     *
     * @param  $project_id
     */
    public function generateReferenceNumber($ref_count): string
    {
        $ref_count = 1000 + $ref_count;
        return (string) $ref_count; // Return as a string to maintain consistency
    }
 
    public function generateLeadRefNum($lead): string
    {
        return $this->generateReferenceNumber(ref_count: $lead->id);
    }


    public function generateUserReferenceNumber($ref_count, $ref_prefix)
    {
        $ref_digits = str_pad($ref_count, 4, 0, STR_PAD_LEFT);
        return $ref_prefix . $ref_digits;
    }
    public function generateUserRefNum($user)
    {
        $prefixes = [
            'Superadmin' => 'SU',
            'Clients' => 'CL',
            'Agency' => 'AG',
            'Presales' => 'PR',
            'Admissionteam' => 'AD',
            'Frontoffice' => 'FR',
            'Agencyanalytics' =>'AA',
            'Qmisadmin'=>'QA',
            'Mdadmin'=>'MD',
        ];
        return $this->generateUserReferenceNumber($user->id, $prefixes[$user->user_type]);
    }

    public function getFilteredLeads($request)
    {
        $user = auth()->user();
        $__global_clients_filter = $this->getGlobalClientsFilter();

        if (!empty($__global_clients_filter)) {
            $project_ids = $this->getClientsProjects($__global_clients_filter);
            $campaign_ids = $this->getClientsCampaigns($__global_clients_filter);
        } else {
            $project_ids = $this->getUserProjects($user);
            $campaign_ids = $this->getCampaigns($user, $project_ids);
        }

        $query = Lead::with(['project', 'campaign', 'source', 'createdBy'])

            ->select(sprintf('%s.*', (new Lead)->getTable()));


        if ($request->has('leads_status')) {
            $leads_status = $request->get('leads_status');

            if ($leads_status == 'duplicate') {
                $query->where('sell_do_is_exist', 1);
            }

            if ($leads_status == 'new') {
                $query->where('sell_do_is_exist', 0);
            }
        }

        if ($user->is_channel_partner_manager) {
            $query->whereHas('createdBy', function ($q) {
                $q->where('user_type', '=', 'ChannelPartner');
            });
        } else {
            $query->where(function ($q) use ($project_ids, $campaign_ids, $user) {
                if ($user->is_channel_partner) {
                    $q->where('created_by', $user->id);
                } else {
                    $q->where(function ($query) {
                        $query->whereRaw("JSON_LENGTH(sub_source_id) > 1")
                            ->orWhereNull('sub_source_id');
                    })
                        ->orWhere(function ($query) {
                            $query->whereJsonDoesntContain('sub_source_id', []);
                        });
                }
            });
        }

        if (!empty($request->input('project_id'))) {
            $query->whereHas('subsource', function ($subSourceQuery) use ($request) {
                $subSourceQuery->where('project_id', $request->input('project_id'));
            });
        }

        if (!empty($request->input('campaign_id'))) {
            $query->whereHas('subsource', function ($subSourceQuery) use ($request) {
                $subSourceQuery->where('campaign_id', $request->input('campaign_id'));
            });
        }

        if (!empty($request->input('source_id'))) {
            $query->whereHas('subsource', function ($subSourceQuery) use ($request) {
                $subSourceQuery->where('source_id', $request->input('source_id'));
            });
        }
        $query->where(function ($q) {
            $q->where('sub_source_id', '<>', 0)
                ->orWhereNull('sub_source_id');
        });



        if (!empty($request->input('sub_source_id'))) {
            $promoIds = $request->input('sub_source_id');
            $query->whereRaw("JSON_CONTAINS(leads.sub_source_id, ?)", [json_encode($promoIds)])
                ->orWhereRaw('JSON_CONTAINS(leads.sub_source_id, ?)', [$promoIds]);

        }

        if (!empty($request->input('no_lead_id')) && $request->input('no_lead_id') === 'true') {
            $query->whereNull('sell_do_lead_id');
        }

        if (!empty($request->input('start_date')) && !empty($request->input('end_date'))) {
            $query->whereDate('created_at', '>=', $request->input('start_date'))
                ->whereDate('created_at', '<=', $request->input('end_date'));
        }

        return $query;
    }


    public function getLeadBySellDoLeadId($sell_do_lead_id)
    {
        $lead = Lead::where('sell_do_lead_id', $sell_do_lead_id)
            ->first();

        return $lead;
    }

    public function getProjectBySellDoProjectId($campaign)
    {
        $sell_do_project_id = $campaign['project_id'] ?? '';

        if (empty($sell_do_project_id)) {
            return [];
        }

        $project = Project::where('outgoing_apis', 'like', '%' . $sell_do_project_id . '%')
            ->first();

        return $project;
    }

    public function generateGuestDocumentViewUrl($id)
    {
        return config('constants.DOCUMENT_URL') . "/document/{$id}/view";
    }

    public function logActivity($lead, $type, $webhook_data, $source = "leads_system")
    {
        LeadEvents::create([
            'source' => $source,
            'lead_id' => $lead->id,
            'sell_do_lead_id' => $lead->sell_do_lead_id,
            'event_type' => $type,
            'webhook_data' => $webhook_data,
        ]);
    }



    public function sendGroupMsg($lead) {
        $body = [];
        // if ($insta) {
            $bot = "6022743104:AAHkqBKlPo_QV535JearoQYTIXJfbaLzcM8";
            $chatId = "-818403912";
            // $bot = "7292482513:AAG8q_123MD4kOS66JPZWQZCfjI7hqkUgbw";
            // $chatId = "5592307888";

            $text = "<b>Father Details:</b>\n";
            foreach($lead->father_details as $f_k => $father) {
                $text .= "<b>".$f_k.":</b>".$father."\n";
            }
            $text = "<b>Mother Details:</b>\n";
            foreach($lead->father_details as $m_k => $mother) {
                $text .= "<b>".$m_k.":</b>".$mother."\n";
            }
            $text .= "<b>Ref Num:</b>".@$lead['ref_num']."\n";
            $headers = array(
                'Accept: application/json',
            );
            // $url = "https://api.telegram.org/bot".$bot."/sendMessage?chat_id=".$chatId."&text=".$text;
            $url = "https://api.telegram.org/bot".$bot."/sendMessage";
            $data = [
                'chat_id' => $chatId,
                'text' => $text,
                'parse_mode' => 'HTML'
            ];
            
            // Use cURL to send the HTTP POST request
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
            
            // Execute the request and get the response
            $response = curl_exec($ch);
            
            // Check for errors
            if (curl_errno($ch)) {
                return false;
            } else {
                return true;
            }
        // }
        return false;
    }
    
    public function createautomation($lead)
    {
        $inbox_fields = json_decode($lead->inbox_fields, true);
        if (@$inbox_fields["Inbox User Id"]) {
            return false;
        }
        $essential_fields = json_decode($lead->essential_fields, true);
        $system_fields = json_decode($lead->system_fields, true);
        // $webhook_response = $lead->webhook_response;
        $webhook_response = json_decode($lead->lead_event_webhook_response, true);

        $sell_do_response = json_decode($lead->sell_do_response, true);
        $sellDoFields = json_decode($lead->sell_do_fields, true);
        $srd = Srd::where('sell_do_project_id', @$webhook_response['payload']['campaign_responses'][0]['project_id'])
        ->where('campaign_name', @$system_fields[0]['Campaign Name'])
        ->where('source', @$system_fields[0]['Source Name'])
        ->where('sub_source', @$system_fields[0]['Sub Source'])
        ->first();
        // Check if decoding was successful and if "Phone Number" exists
        // if ($essential_fields && isset($essential_fields->{'Phone Number'})) {
        //     $phone_number = $essential_fields->{'Phone Number'};
        // } else {
            // Handle the case where "Phone Number" is not found or decoding fails
            // You may log an error, throw an exception, or handle it in another appropriate way
        //     return false;
        // }
        $postdata = [
            "phone" => @$essential_fields['Phone Number'],
            "first_name" => @$essential_fields['Full Name'],
            "last_name" => "",
            "gender" => "",
            "actions" => [
                [
                    "action" => "send_flow",
                    "flow_id" => 11111,
                ],
                [
                    "action" => "add_tag",
                    "tag_name" => "YOU_TAG_NAME",
                ],
                [
                    "action" => "set_field_value",
                    "field_name"=> "Email",
                    "value"=> @$essential_fields[0]['Email']
                ],
                [
                    "action" => "set_field_value",
                    "field_name"=> "alter_email",
                    "value"=> @$essential_fields[0]['Addl Email']
                ],
                [
                    "action" => "set_field_value",
                    "field_name"=> "alter_phone",
                    "value"=>  @$essential_fields[0]['Addl Number']
                ],
                [
                    "action" => "set_field_value",
                    "field_name"=> "campaign_name",
                    "value"=> @$system_fields[0]['Campaign Name']
                ],
                [
                    "action" => "set_field_value",
                    "field_name"=> "funding_source",
                    "value"=> ""
                ],
                [
                    "action" => "set_field_value",
                    "field_name"=> "hotness",
                    "value"=> @$webhook_response['payload']['hotness']
                ],
                [
                    "action" => "set_field_value",
                    "field_name"=> "lms_cp_comments",
                    "value"=> $lead->cp_comments
                ],
                [
                    "action" => "set_field_value",
                    "field_name"=> "lms_enquiry_browser",
                    "value"=> ""
                ],
                [
                    "action" => "set_field_value",
                    "field_name"=> "lms_enquiry_city",
                    "value"=> "0"
                ],
                [
                    "action" => "set_field_value",
                    "field_name"=> "lms_enquiry_comments",
                    "value"=> $lead->comments
                ],
                [
                    "action" => "set_field_value",
                    "field_name"=> "lms_enquiry_os",
                    "value"=> "0"
                ],
                [
                    "action" => "set_field_value",
                    "field_name"=> "lms_enquiry_remarks",
                    "value"=> "0"
                ],
                [
                    "action" => "set_field_value",
                    "field_name"=> "lms_form_id",
                    "value"=> @$system_fields[0]['Form id']
                ],
                [
                    "action" => "set_field_value",
                    "field_name"=> "lms_form_name",
                    "value"=> @$system_fields[0]['Form Name']
                ],
                [
                    "action" => "set_field_value",
                    "field_name"=> "lms_form_url",
                    "value"=> @$system_fields[0]['Form Url']
                ],
                [
                    "action" => "set_field_value",
                    "field_name"=> "lms_ip_address",
                    "value"=> "0"
                ],
                [
                    "action" => "set_field_value",
                    "field_name"=> "lms_lead_date",
                    "value"=> $lead->created_at
                ],
                [
                    "action" => "set_field_value",
                    "field_name"=> "lms_lead_time",
                    "value"=> $lead->created_at
                ],
                [
                    "action" => "set_field_value",
                    "field_name"=> "lms_notes",
                    "value"=> $lead->comments
                ],
                [
                    "action" => "set_field_value",
                    "field_name"=> "lms_project_id",
                    "value"=> $lead->project_id
                ],
                [
                    "action" => "set_field_value",
                    "field_name"=> "lms_ref_num",
                    "value"=> $lead->ref_num
                ],
                [
                    "action" => "set_field_value",
                    "field_name"=> "max_budget",
                    "value"=> $sellDoFields['Max Budget']
                ],
                [
                    "action" => "set_field_value",
                    "field_name"=> "min_budget",
                    "value"=> $sellDoFields['Min Budget']
                ],
                [
                    "action" => "set_field_value",
                    "field_name"=> "notes",
                    "value"=> $lead->comments
                ],
                [
                    "action" => "set_field_value",
                    "field_name"=> "presales_sales_agent",
                    "value"=> @$webhook_response['payload']['sales_name']
                ],
                [
                    "action" => "set_field_value",
                    "field_name"=> "presales_sales_agent_phone",
                    "value"=> ""
                ],
                [
                    "action" => "set_field_value",
                    "field_name"=> "project_name",
                    "value"=> @$system_fields[0]['Project']
                ],
                [
                    "action" => "set_field_value",
                    "field_name"=> "selldo_lead_id",
                    "value"=> @$sell_do_response['sell_do_lead_id']
                ],
                [
                    "action" => "set_field_value",
                    "field_name"=> "selldo_lead_pickup_date",
                    "value"=> @$webhook_response['payload']['recieved_on']
                ],
                [
                    "action" => "set_field_value",
                    "field_name"=> "selldo_lead_pickup_time",
                    "value"=> @$webhook_response['payload']['recieved_on']
                ],
                [
                    "action" => "set_field_value",
                    "field_name"=> "selldo_medium",
                    "value"=> "weebhook"
                ],
                [
                    "action" => "set_field_value",
                    "field_name"=> "selldo_medium_value",
                    "value"=> ""
                ],
                [
                    "action" => "set_field_value",
                    "field_name"=> "selldo_project_id",
                    "value"=> @$webhook_response['payload']['campaign_responses'][0]['project_id']
                ],
                [
                    "action" => "set_field_value",
                    "field_name"=> "selldo_stage",
                    "value"=> @$webhook_response['payload']['stage']
                ],
                [
                    "action" => "set_field_value",
                    "field_name"=> "selldo_status",
                    "value"=> @$webhook_response['payload']['status']
                ],
                [
                    "action" => "set_field_value",
                    "field_name"=> "selldo_team_id",
                    "value"=> @$webhook_response['payload']['team_id']
                ],
                [
                    "action" => "set_field_value",
                    "field_name"=> "selldo_team_name",
                    "value"=> @$webhook_response['payload']['team_name']
                ],
                [
                    "action" => "set_field_value",
                    "field_name"=> "selldo_time",
                    "value"=> @$webhook_response['payload']['recieved_on']
                ],
                [
                    "action" => "set_field_value",
                    "field_name"=> "selldo_user_id",
                    "value"=> ""
                ],
                [
                    "action" => "set_field_value",
                    "field_name"=> "site_visit_schedule_on",
                    "value"=> @$webhook_response['payload']['meta']['next_site_visit_scheduled_on']
                ],
                [
                    "action" => "set_field_value",
                    "field_name"=> "site_visit_status",
                    "value"=> ""
                ],
                [
                    "action" => "set_field_value",
                    "field_name"=> "source_name",
                    "value"=> @$system_fields[0]['Source Name']
                ],
                [
                    "action" => "set_field_value",
                    "field_name"=> "srd_id",
                    "value"=> @$srd->srd
                ],
                [
                    "action" => "set_field_value",
                    "field_name"=> "sub_source",
                    "value"=> @$system_fields[0]['Sub Source']
                ]
            ],
        ];
        $token = '1907249.XtqlzdA7ZDUu5Hr2SQnzO0lN8yuEhYiudCGELk8Dm';
        $headers = array(
            'X-ACCESS-TOKEN: ' . $token,
            'Accept: application/json',
        );
        $url = "https://inbox.thebumblebee.in/api/users";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS,
            json_encode($postdata));

        // Receive server response ...
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $server_output = curl_exec($ch);
        curl_close($ch);

        // $url = "https://inbox.thebumblebee.in/api/users";
        // $method = "post";
        // $server_output = $this->postWebhook($url, $method, $headers, $postdata);

        $server_output = json_decode($server_output, true);
        $id = $server_output['data']['id'];
        // dd($id);
        $inboxFields = [
            'Inbox User Id' => $id,
            'Data Id' => $server_output['data'],
        ];
        $lead = Lead::find($lead->id);
        $lead->inbox_fields = json_encode($inboxFields);
        $lead->save();
        return true;
    }
}
