<?php
use App\Http\Controllers\Admin\StagesController;
use App\Http\Controllers\Admin\WebhookReceiverController;
use App\Http\Controllers\Client\ClientPortalController;
use App\Http\Controllers\Client\EnquiryController;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\Admin\QrCodeController;
use Illuminate\Support\Facades\App;

Route::get('lang/{locale}', function ($locale) {
    session(['locale' => $locale]);
    App::setLocale($locale);

    return redirect()->back();
})->name('locale');


Route::redirect('/', '/client/enquiry-form');
Route::middleware(['revalidate'])->group(function () {

    Route::get('/client/success_g', function() {
        return view('client.success_g');
    })->name('client.success_g');
});
Route::get('/home', function () {
    if (session('status')) {
        return redirect()->route('admin.home')->with('status', session('status'));
    }

    return redirect()->route('admin.home');
});
Route::get('/send-client-portal-link/{lead}', [ClientPortalController::class, 'sendClientPortalLink'])
    ->name('client.sendPortalLink');

Route::get('/client/login', [ClientPortalController::class, 'showLoginForm'])
    ->name('client.login');

    Route::get('/generate-pdf-with-qr-code/{no}', [QrCodeController::class, 'generatePdfWithQrCode'])->name('qrdownload');
// routes/web.php or routes/api.php
Route::post('client/send-otp', [ClientPortalController::class, 'sendOtp'])->name('client.sendOtp');

Route::post('/client/login/verify-otp/{id}', [ClientPortalController::class, 'verifyOtp'])
    ->name('client.login.verifyOtp');
Route::post('/client/verify-otp/{id}', [EnquiryController::class, 'verifyOtp'])
    ->name('client.verifyOtp');

Route::get('/client/logout', [ClientPortalController::class, 'logout'])
    ->name('client.logout');
Auth::routes(['register' => false]);

//webhook receiver
Route::any('webhook/new-lead', 'Admin\WebhookReceiverController@storeNewLead')
    ->name('webhook.store.new.lead');
Route::any('webhook/servetel', 'Admin\WebhookReceiverController@handle')->name('webhook.store.servetel');
Route::any('webhook/lead-activity', 'Admin\WebhookReceiverController@storeLeadActivity')
    ->name('webhook.store.lead.activity');
Route::any('webhook/{secret}', 'Admin\WebhookReceiverController@processor')->name('webhook.processor');
Route::any('webhook/call-record', 'Admin\CallRecordController@storeNewRecord')
    ->name('webhook.store.new.record');


Route::get('document/{id}/view', 'Admin\DocumentController@guestView')->name('document.guest.view');

Route::group(['prefix' => 'admin', 'as' => 'admin.', 'namespace' => 'Admin', 'middleware' => ['auth']], function () {

    Route::post('store-global-client-filters', 'HomeController@storeGlobalClientFilters')
        ->name('store.global.client.filter');

    Route::get('/', 'HomeController@index')->name('home');

    Route::get('incoming-webhook/list', 'WebhookReceiverController@incomingWebhookList')->name('webhook.incoming.list');

    // Permissions
    Route::delete('permissions/destroy', 'PermissionsController@massDestroy')->name('permissions.massDestroy');
    Route::resource('permissions', 'PermissionsController');

    // Roles
    Route::delete('roles/destroy', 'RolesController@massDestroy')->name('roles.massDestroy');
    Route::resource('roles', 'RolesController');

    //sitevist
    Route::resource('/sitevisit', 'SiteVisitController');
    Route::resource('/notes', 'NoteController');
    Route::resource('/notenotinterested', 'NoteNotInterestedController');

    Route::resource('/admissions', 'AdmissionController');
    Route::post('admissions/parse-csv-import', 'AdmissionController@parseCsvImport')->name('admissions.parseCsvImport');
    Route::post('admissions/process-csv-import', 'AdmissionController@processCsvImport')->name('admissions.processCsvImport');
    Route::resource('/followups', 'FollowUpController');
    Route::delete('followups/destroy', 'FollowUpController@massDestroy')->name('followups.massDestroy');
    Route::get('leads/{lead}/initiate-call', 'LeadsController@initiateCall')
        ->name('leads.initiateCall');
        Route::post('/search-lead','LeadsController@searchLead')->name('search-lead');
        Route::post('/validate-otp','LeadsController@validateOtp')->name('validate-otp');
        Route::get('/update-form/{lead_id}', 'LeadsController@showUpdateForm')->name('form.update');
        Route::post('/update-lead/{lead_id}', 'LeadsController@updateLead')->name( 'updateLead');

    Route::put('/admin/sitevisits/{sitevisit}/cancel', 'SiteVisitController@cancelSiteVisit')
        ->name('sitevisits.cancel');
    Route::put('/admin/sitevisits/{sitevisit}/conducted', 'SiteVisitController@conducted')
        ->name('sitevisits.conducted');

    Route::put('/admin/sitevisits/{sitevisit}/notvisited', 'SiteVisitController@notVisited')
        ->name('sitevisits.notvisited');
    Route::put('/admin/sitevisits/{sitevisit}/applicationpurchased', 'SiteVisitController@applicationpurchased')
        ->name('sitevisits.applicationpurchased');
    Route::match(['post', 'put'], '/admin/sitevisits/{id}/reschedule', 'SiteVisitController@reschedule')->name('sitevisits.reschedule');
    Route::match(['post', 'put'], '/admin/sitevisit/reschedule', 'SiteVisitController@rescheduleSiteVisit')->name('sitevisit.reschedule');
    Route::match(['post', 'put'], '/admin/sitevisit/conducted', 'SiteVisitController@conductedstage')->name('sitevisit.conducted');
    Route::match(['post', 'put'], '/admin/sitevisit/notvisited', 'SiteVisitController@notvisitedstage')->name('sitevisit.notvisited');
    Route::match(['post', 'put'], '/admin/sitevisit/cancel', 'SiteVisitController@cancelstage')->name('sitevisit.cancel');

    //call record
    Route::resource('/callog', 'CallRecordController');
    Route::resource('/applications', 'ApplicationController');
    Route::match(['post', 'put'], '/admin/application/applicationaccepted', 'ApplicationController@applicationaccepted')->name('application.applicationaccepted');

    Route::post('applications/parse-csv-import', 'ApplicationController@parseCsvImport')->name('applications.parseCsvImport');
    Route::post('applications/process-csv-import', 'ApplicationController@processCsvImport')->name('applications.processCsvImport');
    Route::resource('/lost','LostController');


    Route::resource('/walkinform', 'WalkinController');
    Route::resource('/importfile', 'FileImportController');
    Route::resource('/subsource', 'SubSourceController');

    Route::resource('/admission', 'AdmissionFollowUpController');
    Route::resource('/application', 'ApplicationNotPurchasedController');
    Route::resource('/admission', 'AdmissionFollowUpController');
    // Users
    Route::get('users/{id}/edit-password', 'UsersController@editPassword')
        ->name('users.edit.password');
    Route::put('users/{id}/update-password', 'UsersController@updatePassword')
        ->name('users.update.password');
    Route::delete('users/destroy', 'UsersController@massDestroy')->name('users.massDestroy');
    Route::resource('users', 'UsersController');

    // Project
    Route::get('project-additional-fields', 'ProjectController@getAdditionalFieldsDropdown')->name('projects.additional.fields');
    Route::get('project-campaigns', 'ProjectController@getCampaignsDropdown')->name('projects.campaigns');
    Route::get('project-campaign-sources', 'ProjectController@getSourceDropdown')->name('projects.campaign.sources');
    Route::get('get-api-constant-row', 'ProjectController@getApiConstantRow')
        ->name('get.api.constant.row.html');
    Route::post('test-webhook', 'ProjectController@postTestWebhook')
        ->name('projects.test.webhook');
    Route::get('get-request-body-row', 'ProjectController@getRequestBodyRow')
        ->name('get.req.body.row.html');
    Route::get('project-webhook-html', 'ProjectController@getWebhookHtml')
        ->name('projects.webhook.html');
    Route::post('store-project-outgoing-webhook', 'ProjectController@saveOutgoingWebhookInfo')
        ->name('project.outgoing.webhook.store');
    Route::get('project/{id}/webhook', 'ProjectController@getWebhookDetails')
        ->name('projects.webhook');
    Route::delete('projects/destroy', 'ProjectController@massDestroy')->name('projects.massDestroy');
    Route::post('projects/media', 'ProjectController@storeMedia')->name('projects.storeMedia');
    Route::post('projects/ckmedia', 'ProjectController@storeCKEditorImages')->name('projects.storeCKEditorImages');
    Route::post('projects/parse-csv-import', 'ProjectController@parseCsvImport')->name('projects.parseCsvImport');
    Route::post('projects/process-csv-import', 'ProjectController@processCsvImport')->name('projects.processCsvImport');
    Route::resource('projects', 'ProjectController');

    // Campaign
    Route::get('get-campaigns', 'CampaignController@getCampaigns')->name('get.campaigns');
    Route::get('get-razorpay', 'LeadsController@razorLog')->name('get.razorLogs');
    Route::get('get-email', 'LeadsController@emailLog')->name('get.mailLogs');
    Route::delete('campaigns/destroy', 'CampaignController@massDestroy')->name('campaigns.massDestroy');
    Route::resource('campaigns', 'CampaignController');
    Route::resource('tags', "TagController");


    Route::resource('srd', 'SrdController');
    Route::resource('call', 'CallLogController');
    Route::resource('fields', 'FieldController');

   //url
   Route::resource('urls', 'UrlController');
   Route::put('urls/{id}/generate-url', 'UrlController@generateUrl')->name('urls.generate-url');
   Route::get('urls/incoming-webhook/{url_id}/{sub_source_name}', 'UrlController@getWebhookDetails')->name('urls.webhook');
   Route::post('urls/update-email-and-phone-key', 'UrlController@updatePhoneAndEmailKey')->name('url.update.email.and.phone.key');
   Route::get('leads/projects/{id}', 'LeadsController@projectViseLeads')->name('leads.projects');
    //Stages
    Route::resource('/parent-stages', "ParentStageController");
    Route::resource('/stages', "StagesController");

    // Leads
    Route::get('share-lead/{lead_id}/doc/{doc_id}', 'LeadsController@shareDocument')->name('share.lead.doc');
    Route::get('leads/export', 'LeadsController@export')->name('leads.export');
    Route::post('send-mass-webhook', 'LeadsController@sendMassWebhook')->name('lead.send.mass.webhook');
    Route::get('lead-details-rows-html', 'LeadsController@getLeadDetailsRows')->name('lead.details.rows');
    Route::get('lead-detail-html', 'LeadsController@getLeadDetailHtml')->name('lead.detail.html');
    Route::delete('leads/destroy', 'LeadsController@massDestroy')->name('leads.massDestroy');
    Route::resource('leads', 'LeadsController');
    Route::post('leads/parse-csv-import', 'LeadsController@parseCsvImport')->name('leads.parseCsvImport');
    Route::post('leads/process-csv-import', 'LeadsController@processCsvImport')->name('leads.processCsvImport');

    // Audit Logs
    Route::resource('audit-logs', 'AuditLogsController', ['except' => ['create', 'store', 'edit', 'update', 'destroy']]);

    // Client
    Route::delete('clients/destroy', 'ClientController@massDestroy')->name('clients.massDestroy');
    Route::post('clients/media', 'ClientController@storeMedia')->name('clients.storeMedia');
    Route::post('clients/ckmedia', 'ClientController@storeCKEditorImages')->name('clients.storeCKEditorImages');
    Route::resource('clients', 'ClientController');
    Route::resource('report', 'ReportController');

    // Agency
    Route::delete('agencies/destroy', 'AgencyController@massDestroy')->name('agencies.massDestroy');
    Route::resource('agencies', 'AgencyController');
    Route::resource('lead-activity', 'LeadActivityController');
    Route::resource('file-import', 'FileImportController');

    Route::resource('plans', "PlanController");
    Route::resource('packages', "PackageController");
    // Source
    Route::get('source/{id}/webhook', 'SourceController@getWebhookDetails')
        ->name('sources.webhook');
    Route::post('update-email-and-phone-key', 'SourceController@updatePhoneAndEmailKey')->name('update.email.and.phone.key');
    Route::get('get-sources', 'SourceController@getSource')->name('get.sources');
    Route::delete('sources/destroy', 'SourceController@massDestroy')->name('sources.massDestroy');
    Route::resource('sources', 'SourceController');
    Route::resource('stage-notes', 'StageNotesController');


    Route::get('system-calendar', 'SystemCalendarController@index')->name('systemCalendar');
    Route::get('global-search', 'GlobalSearchController@search')->name('globalSearch');

    // Document
    Route::get('document-logs', 'DocumentController@getDocumentLogs')->name('get.documents.log');
    Route::get('get-filtered-documents', 'DocumentController@getFilteredDocuments')->name('documents.filtered');
    Route::delete('documents/{id}/file-remove', 'DocumentController@removeFile')->name('documents.remove.file');
    Route::post('documents/ckmedia', 'DocumentController@storeCKEditorImages')->name('documents.storeCKEditorImages');
    Route::delete('documents/destroy', 'DocumentController@massDestroy')->name('documents.massDestroy');
    Route::resource('documents', 'DocumentController');
});
Route::group(['prefix' => 'profile', 'as' => 'profile.', 'namespace' => 'Auth', 'middleware' => ['auth']], function () {
    // Change password
    if (file_exists(app_path('Http/Controllers/Auth/ChangePasswordController.php'))) {
        Route::get('password', 'ChangePasswordController@edit')->name('password.edit');
        Route::post('password', 'ChangePasswordController@update')->name('password.update');
        Route::post('profile', 'ChangePasswordController@updateProfile')->name('password.updateProfile');
        Route::post('profile/destroy', 'ChangePasswordController@destroy')->name('password.destroyProfile');
    }
});

Route::group(['prefix' => 'client', 'as' => 'client.', 'namespace' => 'Client'], function () {
Route::middleware(['revalidate'])->group(function () {

    Route::get('/parent-details/{id}', 'FormController@parentDetails')->name('parentDetails');
    Route::put('/parent-details/{id}', 'FormController@updateParentDetails')->name('updateParentDetails');
    Route::get('/student-details/{id}', 'FormController@studentDetails')->name('studentDetails');
    Route::put('/student-details/{id}', 'FormController@updateStudentDetails')->name('updateStudentDetails');
    Route::get('/scheme-details/{id}', 'SchemeController@schemeDetails')->name('schemeDetails');
    Route::post('/choose-scheme/{id}', 'SchemeController@chooseSchemeOption')->name('chooseSchemeOption');
    Route::post('/payment/{id}', 'PaymentController@createOrder')->name('createOrder');
    Route::post('/payment/complete/{id}', 'PaymentController@paymentSuccess')->name('paymentSuccess');
    Route::get('/paymentCom/complete/{id}', 'PaymentController@paymentCom')->name('paymentCom');
    Route::get('/site-visit/{id}/{sv_id}', 'SiteVisitController@showForm')->name('siteVisitForm');
    Route::post('/site-visit/storesitevisit/{id}', 'SiteVisitController@store')->name('storeSiteVisit');
    Route::get('/application/{id}', 'ClientPortalController@application')->name('application');
    Route::get('/accreditions/{id}', 'FormController@accreditions')->name('accreditions');
    Route::get('/initiatives/{id}', 'FormController@initiatives')->name('initiatives');
    Route::get('/qmis-learning/{id}', 'FormController@qmisLearning')->name('qmisLearning');
    Route::get('/early-child/{id}', 'FormController@earlyChild')->name('earlyChild');
    Route::get('/eligiblity-criteria/{id}', 'FormController@eligiblity_criteria')->name('eligiblity_criteria');
    Route::get('/admission-process/{id}', 'FormController@admission_process')->name('admission_process');
    Route::get('/kg-program/{id}', 'FormController@kg_program')->name('kg_program');
    Route::get('/global-standards/{id}', 'FormController@global_standards')->name('global_standards');
    Route::get('/createpayment/{id}', 'PaymentController@payment')->name('createpayment');
    Route::get('/paymentcomplete/{id}', 'PaymentController@paymentSuccess')->name('paymentCompletion');
    Route::post('site-visit/payment/complete/{id}/{sv_id}', 'SiteVisitController@paymentSuccess')->name('siteVisit.paymentSuccess');
    Route::get('site-visit/paymentCom/complete/{id}/{sv_id}', 'SiteVisitController@paymentCom')->name('siteVisit.paymentCom');
    Route::get('site-visit/createpayment/{id}/{sv_id}', 'SiteVisitController@payment')->name('siteVisit.createpayment');
    Route::get('site-visit/paymentcomplete/{id}/{sv_id}', 'SiteVisitController@paymentSuccess')->name('siteVisit.paymentCompletion');
    Route::get('/application-process/{id}', 'PaymentController@applicationProcess')->name('application_process');
    Route::post('/resend-otp/{id}', 'EnquiryController@resendOtp')->name('resendOtp');
    Route::get('sv/structure/{id}/{sv_id}', 'SiteVisitController@structure')->name(name: 'sv.structure');


    Route::get('/splash', 'ClientPortalController@splash')->name('splash');
    Route::get('/welcome', 'ClientPortalController@welcome')->name('welcome');

    Route::get('/enquiry-form', 'EnquiryController@enquiry')->name('enquiryForm');
    // Route::post('/enquiry-form/store', 'EnquiryController@storeEnquiry')->name('storeEnquiry');
    Route::post('enquiry-form/store','EnquiryController@storeEnquiry')->name('storeEnquiry');

    Route::post('/enquiry/verify-otp/{id}', 'EnquiryController@verifyOtp')
        ->name('enquiry.verifyOtp');

    Route::get('/success/{id}', 'SiteVisitController@success')->name('success');

    Route::get('/onboard/{id}', 'FormController@onboard')->name('onboard');
    Route::get('/academic/{id}', 'FormController@academic')->name('academic');
    Route::get('/school/{id}', 'FormController@school')->name('school');
    Route::get('/program/{id}', 'FormController@program')->name('program');
    Route::get('/play/{id}', 'FormController@play')->name('play');
    Route::get('/one-step/{id}', 'FormController@oneStep')->name('oneStep');

    Route::get('/structure/{id}', 'FormController@structure')->name('structure');
    Route::get('/structure_detail/{type}', 'FormController@structure_detail')->name('structure_detail');
});


});
Route::get('/send-test-email', function () {
    $details = [
        'title' => 'Test Email from Laravel',
        'body' => 'This is a test email sent using Gmail SMTP in Laravel.'
    ];

    Mail::to('nathiya@sarashgroup.com')->send(new \App\Mail\TestMail($details));

    return 'Test email sent!';
});
Route::group(['prefix' => 'walkin', 'as' => 'walkin.', 'namespace' => 'Walkin'], function () {
    Route::resource('/', 'WalkinController');
    Route::get('/', 'WalkinController@direct')->name('direct');
    Route::post('/search-lead','WalkinController@searchLead')->name('search-lead');
Route::post('/validate-otp','WalkinController@validateOtp')->name('validate-otp');
Route::get('/update-form/{lead_id}', 'WalkinController@showUpdateForm')->name('form.update');
Route::post('/update-lead/{lead_id}', 'WalkinController@updateLead')->name('updateLead');
    });
    