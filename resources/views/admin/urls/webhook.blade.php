@extends('layouts.admin')

@section('content')
    <div class="row mb-2">
        <div class="col-sm-12">
            <h2>
                @lang('messages.configure_webhook_details')
                <small>
                    <strong>URL:</strong>
                    <span class="text-primary">{{ $sub_source_name }}</span>
                    <strong>Project:</strong>
                    <span class="text-primary">{{ optional($srd->project)->name }}</span>
                </small>
            </h2>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <div class="row">
                        <div class="col-md-6">
                            <h3 class="card-title">
                                {{ trans('messages.receive_webhook') }}
                            </h3>
                        </div>
                        <div class="col-md-6">
                            <a class="btn btn-default float-right" href="{{ route('admin.urls.index') }}">
                                <i class="fas fa-chevron-left"></i>
                                {{ trans('global.back_to_list') }}
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group webhook_div">
                                    <label for="webhook_url">
                                        {{ trans('messages.webhook_url') }}
                                    </label>
                                    <div class="input-group">
                                        <input type="text" id="webhook_url"
                                            value="{{ route('webhook.processor', ['secret' => $webhook_secret]) }}"
                                            class="form-control cursor-pointer copy_link" readonly>
                                        <div class="input-group-append cursor-pointer copy_link">
                                            <span class="input-group-text">
                                                <i class="fas fa-copy"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-5">
                            <div class="col-md-12 d-flex justify-content-between mb-2">
                                <h3>
                                    {{ trans('messages.most_recent_lead') }}
                                </h3>
                                <button type="button" class="btn btn-outline-primary btn-xs refresh_latest_lead">
                                    <i class="fas fa-sync"></i>
                                    {{ trans('messages.refresh') }}
                                </button>
                            </div>
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>{{ trans('messages.key') }}</th>
                                                <th>{{ trans('messages.value') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if (!empty($lead) && !empty($lead->payload))
                                                @php
                                                    $payload = is_string($lead->payload) ? json_decode($lead->payload, true) : $lead->payload;
                                                @endphp
                                                @if (is_array($payload) || is_object($payload))
                                                    @foreach ($payload as $key => $value)
                                                        <tr>
                                                            <td>{{ $key }}</td>
                                                            <td>{{ $value }}</td>
                                                        </tr>
                                                    @endforeach
                                                @else
                                                    <tr>
                                                        <td colspan="2" class="text-center">
                                                            {{ trans('messages.no_data_found') }}
                                                        </td>
                                                    </tr>
                                                @endif
                                            @else
                                                <tr>
                                                    <td colspan="2" class="text-center">
                                                        <span class="text-center">
                                                            {{ trans('messages.no_data_found') }}
                                                        </span>
                                                    </td>
                                                </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <form action="{{ route('admin.url.update.email.and.phone.key') }}" method="post"
                                    enctype="multipart/form-data">
                                    @csrf
                                    <input type="hidden" name="sub_source_id" value="{{ $srd->id }}" id="url_id">
                                    <input type="hidden" name="sub_source_name" value="{{ $sub_source_name }}">
                                    <input type="hidden" name="webhook_secret" value="{{ $webhook_secret }}">

                                    <div class="row">
                                        @php
                                            $fields = $srd->project->fields;
                                        @endphp
                                        @if (is_array($fields) || is_object($fields))
                                            @foreach ($fields as $key => $mappedField)
                                                @if (isset($mappedField['enabled']) && $mappedField['enabled'] === true)
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="name">{{ $mappedField['text'] }}</label><br>
                                                            <label for="name">{{ $mappedField['value'] }}</label><br>
                                                            <input type="hidden"
                                                                name="mapped_fields[{{ $key }}][text]"
                                                                value="{{ $mappedField['text'] }}" class="form-control"
                                                                readonly>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <br>
                                                            <select class="form-control select2"
                                                                name="mapped_fields[{{ $key }}][value]"
                                                                id="text">
                                                                <option value="">@lang('messages.please_select')</option>
                                                                @foreach ($existing_keys as $existing_key => $value)
                                                                    <option value="{{ $existing_key }}"
                                                                        data-value="{{ $value }}"
                                                                        {{ metaphone($existing_key) === metaphone($mappedField['value']) ? 'selected' : '' }}>
                                                                        {{ $existing_key }}: {{ $value }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                @endif
                                            @endforeach
                                        @else
                                            {{-- <div class="col-md-12 text-center">
                                                <span class="text-danger">{{ trans('messages.no_fields_found') }}</span>
                                            </div> --}}
                                        @endif
                                    </div>
                                    <div class="row mt-3">
                                        <div class="col-md-4">
                                            <button type="submit" class="btn btn-outline-primary">
                                                {{ trans('messages.save') }}
                                            </button>
                                        </div>
                                    </div>
                                </form>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(function() {
            $(document).on('click', '.copy_link', function() {
                copyToClipboard($("#webhook_url").val());
            });

            function copyToClipboard(text) {
                const textarea = document.createElement('textarea');
                textarea.value = text;
                document.body.appendChild(textarea);
                textarea.select();
                document.execCommand('copy');
                document.body.removeChild(textarea);

                const span = document.createElement('span');
                span.innerText = 'Link copied to clipboard!';
                $(".webhook_div").append(span);
                setTimeout(() => {
                    span.remove();
                }, 300);
            }

            $(document).on('click', '.refresh_latest_lead', function() {
                location.reload();
            });
        });
    </script>
@endsection
