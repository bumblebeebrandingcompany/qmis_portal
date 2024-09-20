<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyProjectRequest;
use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use App\Models\Clients;
use App\Models\Project;
use App\Models\User;
use App\Models\Source;
use App\Models\Campaign;
use Gate;
use App\Models\Client;
use App\Models\Field;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;
use App\Utils\Util;
use GuzzleHttp\Exception\RequestException;

class ProjectController extends Controller
{
    use MediaUploadingTrait, CsvImportTrait;

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

    public function index(Request $request)
    {
        $user=auth()->user();
        if ($request->ajax()) {

            $__global_clients_filter = $this->util->getGlobalClientsFilter();

            $query = Project::with(['created_by', 'client'])->select(sprintf('%s.*', (new Project)->table));

            // Remove user-specific filtering and permission checks
            if (!empty($__global_clients_filter)) {
                $query->whereIn('projects.client_id', $__global_clients_filter);
            }

            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row)use($user) {
                // Remove permission checks for actions
                $viewGate = $user->is_superadmin;
                $editGate = $user->is_superadmin;
                $deleteGate = $user->is_superadmin;
                $outgoingWebhookGate = $user->is_superadmin;
                $crudRoutePart = 'projects';

                return view(
                    'partials.datatablesActions',
                    compact(
                        'viewGate',
                        'editGate',
                        'deleteGate',
                        'outgoingWebhookGate',
                        'crudRoutePart',
                        'row'
                    )
                );
            });

            $table->editColumn('ref_num', function ($row) {
                return $row->ref_num ? $row->ref_num : '';
            });

            $table->editColumn('name', function ($row) {
                return $row->name ? $row->name : '';
            });

            $table->addColumn('created_by_name', function ($row) {
                return $row->created_by ? $row->created_by->name : '';
            });

            $table->addColumn('client_name', function ($row) {
                return $row->client ? $row->client->name : '';
            });

            $table->editColumn('client.email', function ($row) {
                return $row->client ? (is_string($row->client) ? $row->client : $row->client->email) : '';
            });

            $table->editColumn('location', function ($row) {
                return $row->location ? $row->location : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'created_by', 'client']);

            return $table->make(true);
        }

        $users = User::get();
        $clients = Client::get();

        return view('admin.projects.index', compact('users', 'clients'));
    }
        public function create()
    {
        // if (!auth()->user()->checkPermission('project_create')) {
        //     abort(403, 'Unauthorized.');
        // }

        $created_bies = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $clients = Client::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        $fields = Field::all();

        return view('admin.projects.create', compact('clients', 'created_bies', 'fields'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'nullable|string|max:255',
        ]);

        $fields = $request->input('fields');
        foreach ($fields as $key => $field) {
            $fields[$key]['enabled'] = filter_var($field['enabled'], FILTER_VALIDATE_BOOLEAN);
        }

        $project = new Project();
        $project->name = $request->input('name');
        $project->location = $request->input('location');
        $project->created_by_id = auth()->user()->id;
        $project->fields = $fields;

        $nameInitials = strtoupper(substr($request->input('name'), 0, 2));

        $lastProject = Project::where('ref_num', 'like', $nameInitials . '%')
            ->orderBy('ref_num', 'desc')
            ->first();

        if ($lastProject) {
            $lastNumber = intval(substr($lastProject->ref_num, 2));
            $newNumber = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '001';
        }

        $project->ref_num = $nameInitials . $newNumber;

        $project->save();

        return redirect()->route('admin.projects.index');
    }


    public function edit(Project $project)
    {
        // if (!auth()->user()->checkPermission('project_edit')) {
        //     abort(403, 'Unauthorized.');
        // }

        $created_bies = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $clients = Client::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $project->load('created_by', 'client');
        $fields = $project->fields;
        return view('admin.projects.edit', compact('clients', 'created_bies', 'project', 'fields'));
    }

    public function update(UpdateProjectRequest $request, Project $project)
    {
        abort_if((auth()->user()->is_agency || auth()->user()->is_channel_partner || auth()->user()->is_channel_partner_manager), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $project_details = $request->except(['_method', '_token']);

        $customFields = $request->input('custom_fields', []);
        $project_details['custom_fields'] = array_merge($customFields);

        $essentialFields = $request->input('essential_fields', []);
        $project_details['essential_fields'] = array_merge($essentialFields);

        $salesFields = $request->input('sales_fields', []);
        $project_details['sales_fields'] = array_merge($salesFields);

        // Extract system fields from the request
        $systemFields = $request->input('system_fields', []);
        $project_details['system_fields'] = array_merge($systemFields);

        // Update the model with the array
        $project->update($project_details);

        return redirect()->route('admin.projects.index');
    }

    public function show(Project $project)
    {
        abort_if((auth()->user()->is_agency || auth()->user()->is_channel_partner || auth()->user()->is_channel_partner_manager), Response::HTTP_FORBIDDEN, '403 Forbidden');

        // $project->load('user', 'client');

        return view('admin.projects.show', compact('project'));
    }

    public function destroy(Project $project)
    {
        abort_if(!auth()->user()->is_superadmin, Response::HTTP_FORBIDDEN, '403 Forbidden');

        $project->delete();

        return back();
    }

    public function massDestroy(MassDestroyProjectRequest $request)
    {
        abort_if(!auth()->user()->is_superadmin, Response::HTTP_FORBIDDEN, '403 Forbidden');

        $projects = Project::find(request('ids'));

        foreach ($projects as $project) {
            $project->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if((auth()->user()->is_agency || auth()->user()->is_channel_partner || auth()->user()->is_channel_partner_manager), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model = new Project();
        $model->id = $request->input('crud_id', 0);
        $model->exists = true;
        $media = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }

    public function getWebhookDetails($id)
    {
        if (!auth()->user()->is_superadmin) {
            abort(403, 'Unauthorized.');
        }

        $project = Project::findOrFail($id);

        return view('admin.projects.webhook', compact('project'));
    }

    public function saveOutgoingWebhookInfo(Request $request)
    {
        $id = $request->input('project_id');
        $api = $request->input('api');

        $project = Project::findOrFail($id);
        $project->outgoing_apis = $api;
        $project->save();

        $this->util->storeUniqueWebhookFieldsWhenCreatingWebhook($project);

        return redirect()->route('admin.projects.webhook', $project->id);
    }

    public function getWebhookHtml(Request $request)
    {
        if ($request->ajax()) {
            $type = $request->get('type');
            $key = $request->get('key');
            $project_id = $request->input('project_id');
            if ($type == 'api') {
                $tags = $this->util->getWebhookFieldsTags($project_id);
                return view('admin.projects.partials.api_card')
                    ->with(compact('key', 'tags'));
            } else {
                return view('admin.projects.partials.webhook_card')
                    ->with(compact('key'));
            }
        }
    }
    public function getRequestBodyRow(Request $request)
    {
        if ($request->ajax()) {
            $project_id = $request->input('project_id');
            $webhook_key = $request->get('webhook_key');
            $rb_key = $request->get('rb_key');


            // $customFields = [];

            if (!empty($project_id)) {
                $project = Project::find($project_id);
                if ($project) {
                    $customFields = $project->custom_fields ?? [];
                    $essentialFields = $project->essential_fields ?? [];
                    $salesFields = $project->sales_fields ?? [];
                    $systemFields = $project->system_fields ?? [];
                }
            }

            // Log::info('Custom Fields:', json_encode($customFields));

            return view('admin.projects.partials.request_body_input')
                ->with('webhook_key', $webhook_key)
                ->with('rb_key', $rb_key)
                ->with('customFields', $customFields)
                ->with('essentialFields', $essentialFields)
                ->with('salesFields', $salesFields)
                ->with('systemFields', $systemFields);
        }
    }


    public function postTestWebhook(Request $request)
    {
        try {
            $api = $request->input('api');
            $response = null;
            foreach ($api as $api_detail) {
                if (
                    !empty($api_detail['url'])
                ) {
                    $body = $this->getDummyDataForApi($api_detail);
                    $constants = $this->util->getApiConstants($api_detail);
                    $body = array_merge($body, $constants);
                    $headers['secret-key'] = $api_detail['secret_key'] ?? '';

                    //merge query parameter into request body
                    $queryString = parse_url($api_detail['url'], PHP_URL_QUERY);
                    parse_str($queryString, $queryArray);
                    $body = array_merge($body, $queryArray);

                    $response = $this->util->postWebhook($api_detail['url'], $api_detail['method'], $headers, $body);
                } else {
                    return ['success' => false, 'msg' => __('messages.url_is_required')];
                }
            }
            $output = ['success' => true, 'msg' => __('messages.success'), 'response' => $response];
        } catch (RequestException $e) {
            $msg = $e->getMessage() ?? __('messages.something_went_wrong');
            $output = ['success' => false, 'msg' => $msg];
        }
        return $output;
    }

    public function getDummyDataForApi($api)
    {
        $request_body = $api['request_body'] ?? [];
        if (empty($request_body)) {
            return [];
        }

        $dummy_data = [];
        foreach ($request_body as $value) {
            if (!empty($value['key'])) {
                $dummy_data[$value['key']] = 'test data';
            }
        }

        return $dummy_data;
    }

    public function getApiConstantRow(Request $request)
    {
        if ($request->ajax()) {
            $webhook_key = $request->get('webhook_key');
            $constant_key = $request->get('constant_key');
            return view('admin.projects.partials.constants')
                ->with(compact('webhook_key', 'constant_key'));
        }
    }

    public function getCampaignsDropdown(Request $request)
    {
        if ($request->ajax()) {
            $campaigns = Campaign::where('project_id', $request->input('project_id'))
                ->pluck('name', 'id')
                ->toArray();

            return view('admin.projects.partials.campaigns_dropdown')
                ->with(compact('campaigns'));
        }
    }

    public function getSourceDropdown(Request $request)
    {
        if ($request->ajax()) {
            $sources = Source::where('project_id', $request->input('project_id'))
                ->where('campaign_id', $request->input('campaign_id'))
                ->pluck('name', 'id')
                ->toArray();

            return view('admin.projects.partials.sources_dropdown')
                ->with(compact('sources'));
        }
    }

    public function getAdditionalFieldsDropdown(Request $request)
    {
        if ($request->ajax()) {
            $project = Project::where('id', $request->input('project_id'))
                ->firstOrFail();

            return view('admin.projects.partials.additional_fields_dropdown')
                ->with(compact('project'));
        }
    }
}
