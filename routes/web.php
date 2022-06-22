<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
// Auth::routes();
// Route::get('/login/{lang?}', 'Auth\LoginController@showLoginForm')->name('login');
// Route::get('/password/resets/{lang?}', 'Auth\LoginController@showLinkRequestForm')->name('change.langPass');

require __DIR__.'/auth.php';

Route::get('/', 'DashboardController@index')->name('dashboard')->middleware(
    [
        'XSS',
        'revalidate',
    ]
);

Route::get('/dashboard', 'DashboardController@index')->name('dashboard')->middleware(
    [
        'auth',
        'XSS',
        'revalidate',
    ]
);


Route::group(
    [
        'middleware' => [
            'auth',
            'XSS',
            'revalidate',
        ],
    ], function (){
    Route::post('edit-employee-company-info/{id}', 'EmployeeController@employeeCompanyInfoEdit')->name('employee.company.update');
    Route::post('edit-employee-personal-info/{id}', 'EmployeeController@employeePersonalInfoEdit')->name('employee.personal.update');
    Route::post('edit-employee-bank-info/{id}', 'EmployeeController@employeeBankInfoEdit')->name('employee.bank.update');

    Route::resource('employee', 'EmployeeController');
}
);
Route::any('employee-reset-password/{id}', 'EmployeeController@employeePassword')->name('employee.reset');
Route::post('employee-reset-password/{id}', 'EmployeeController@employeePasswordReset')->name('employee.password.update');

Route::group(
    [
        'middleware' => [
            'auth',
            'XSS',
            'revalidate',
        ],
    ], function (){
    Route::resource('client', 'ClientController');
}
);

Route::any('client-reset-password/{id}', 'ClientController@clientPassword')->name('client.reset');
Route::post('client-reset-password/{id}', 'ClientController@clientPasswordReset')->name('client.password.update');

Route::group(
    [
        'middleware' => [
            'auth',
            'XSS',
            'revalidate',
        ],
    ], function (){
    Route::resource('department', 'DepartmentController');
}
);
Route::group(
    [
        'middleware' => [
            'auth',
            'XSS',
            'revalidate',
        ],
    ], function (){
    Route::resource('designation', 'DesignationController');
}
);
Route::group(
    [
        'middleware' => [
            'auth',
            'XSS',
            'revalidate',
        ],
    ], function (){
    Route::resource('salaryType', 'SalaryTypeController');
}
);
Route::group(
    [
        'middleware' => [
            'auth',
            'XSS',
            'revalidate',
        ],
    ], function (){
    Route::get('bulk-attendance', 'AttendanceController@bulkAttendance')->name('bulk.attendance');
    Route::post('bulk-attendance', 'AttendanceController@bulkAttendanceData')->name('bulk.attendance');

    Route::post('employee/attendance', 'AttendanceController@attendance')->name('employee.attendance');
    Route::resource('attendance', 'AttendanceController');
}
);
Route::group(
    [
        'middleware' => [
            'auth',
            'XSS',
            'revalidate',
        ],
    ], function (){
    Route::resource('holiday', 'HolidayController');
}
);

Route::group(
    [
        'middleware' => [
            'auth',
            'XSS',
            'revalidate',
        ],
    ], function (){
    Route::get('leave/{id}/action', 'LeaveController@action')->name('leave.action');
    Route::post('leave/changeAction', 'LeaveController@changeAction')->name('leave.changeaction');
    Route::post('leave/jsonCount', 'LeaveController@jsonCount')->name('leave.jsoncount');
    Route::resource('leave', 'LeaveController');
}
);

Route::group(
    [
        'middleware' => [
            'auth',
            'XSS',
            'revalidate',
        ],
    ], function (){
    Route::resource('leaveType', 'LeaveTypeController');
}
);

Route::group(
    [
        'middleware' => [
            'auth',
            'XSS',
            'revalidate',
        ],
    ], function (){
    Route::get('meeting/calendar', 'MeetingController@calendar')->name('meeting.calendar');
    Route::resource('meeting', 'MeetingController');
}
);

Route::group(
    [
        'middleware' => [
            'auth',
            'XSS',
            'revalidate',
        ],
    ], function (){
    Route::get('lead/grid', 'LeadController@grid')->name('lead.grid');
    Route::post('lead/json', 'LeadController@json')->name('lead.json');
    Route::post('lead/order', 'LeadController@order')->name('lead.order');
    Route::get('lead/{id}/users', 'LeadController@userEdit')->name('lead.users.edit');
    Route::post('lead/{id}/users', 'LeadController@userUpdate')->name('lead.users.update');
    Route::delete('lead/{id}/users/{uid}', 'LeadController@userDestroy')->name('lead.users.destroy');

    Route::get('lead/{id}/items', 'LeadController@productEdit')->name('lead.items.edit');
    Route::post('lead/{id}/items', 'LeadController@productUpdate')->name('lead.items.update');
    Route::delete('lead/{id}/items/{uid}', 'LeadController@productDestroy')->name('lead.items.destroy');

    Route::post('lead/{id}/file', 'LeadController@fileUpload')->name('lead.file.upload');
    Route::get('lead/{id}/file/{fid}', 'LeadController@fileDownload')->name('lead.file.download');
    Route::delete('lead/{id}/file/delete/{fid}', 'LeadController@fileDelete')->name('lead.file.delete');

    Route::get('lead/{id}/sources', 'LeadController@sourceEdit')->name('lead.sources.edit');
    Route::post('lead/{id}/sources', 'LeadController@sourceUpdate')->name('lead.sources.update');
    Route::delete('lead/{id}/sources/{uid}', 'LeadController@sourceDestroy')->name('lead.sources.destroy');

    Route::get('lead/{id}/discussions', 'LeadController@discussionCreate')->name('lead.discussions.create');
    Route::post('lead/{id}/discussions', 'LeadController@discussionStore')->name('lead.discussion.store');

    Route::get('lead/{id}/call', 'LeadController@callCreate')->name('lead.call.create');
    Route::post('lead/{id}/call', 'LeadController@callStore')->name('lead.call.store');
    Route::get('lead/{id}/call/{cid}/edit', 'LeadController@callEdit')->name('lead.call.edit');
    Route::post('lead/{id}/call/{cid}', 'LeadController@callUpdate')->name('lead.call.update');
    Route::delete('lead/{id}/call/{cid}', 'LeadController@callDestroy')->name('lead.call.destroy');

    Route::get('lead/{id}/email', 'LeadController@emailCreate')->name('lead.email.create');
    Route::post('lead/{id}/email', 'LeadController@emailStore')->name('lead.email.store');

    Route::get('lead/{id}/label', 'LeadController@labels')->name('lead.label');
    Route::post('lead/{id}/label', 'LeadController@labelStore')->name('lead.label.store');


    Route::get('lead/{id}/show_convert', 'LeadController@showConvertToDeal')->name('lead.convert.deal');
    Route::post('lead/{id}/convert', 'LeadController@convertToDeal')->name('lead.convert.to.deal');


    Route::get('lead/{id}/show_convert', 'LeadController@showConvertToDeal')->name('lead.convert.deal');
    Route::post('lead/{id}/convert', 'LeadController@convertToDeal')->name('lead.convert.to.deal');

    Route::post('lead/change-pipeline', 'LeadController@changePipeline')->name('lead.change.pipeline');
    Route::resource('lead', 'LeadController');
}
);
Route::post('lead/{id}/note', 'LeadController@noteStore')->name('lead.note.store')->middleware(['auth']);
Route::group(
    [
        'middleware' => [
            'auth',
            'XSS',
            'revalidate',
        ],
    ], function (){
    Route::resource('pipeline', 'PipelineController');
}
);


Route::group(
    [
        'middleware' => [
            'auth',
            'XSS',
            'revalidate',
        ],
    ], function (){
    Route::post('leadStage/order', 'LeadStageController@order')->name('leadStage.order');
    Route::resource('leadStage', 'LeadStageController');
}
);

Route::group(
    [
        'middleware' => [
            'auth',
            'XSS',
            'revalidate',
        ],
    ], function (){
    Route::resource('source', 'SourceController');
}
);

Route::group(
    [
        'middleware' => [
            'auth',
            'XSS',
            'revalidate',
        ],
    ], function (){
    Route::resource('label', 'LabelController');
}
);

Route::group(
    [
        'middleware' => [
            'auth',
            'XSS',
            'revalidate',
        ],
    ], function (){
    Route::resource('taxRate', 'TaxRateController');
}
);

Route::group(
    [
        'middleware' => [
            'auth',
            'XSS',
            'revalidate',
        ],
    ], function (){
    Route::resource('unit', 'UnitController');
}
);

Route::group(
    [
        'middleware' => [
            'auth',
            'XSS',
            'revalidate',
        ],
    ], function (){
    Route::resource('category', 'CategoryController');
}
);

Route::group(
    [
        'middleware' => [
            'auth',
            'XSS',
            'revalidate',
        ],
    ], function (){

    Route::post('deal/order', 'DealController@order')->name('deal.order');
    Route::get('deal/{id}/users', 'DealController@userEdit')->name('deal.users.edit');
    Route::post('deal/{id}/users', 'DealController@userUpdate')->name('deal.users.update');
    Route::delete('deal/{id}/users/{uid}', 'DealController@userDestroy')->name('deal.users.destroy');

    Route::get('deal/{id}/items', 'DealController@productEdit')->name('deal.items.edit');
    Route::post('deal/{id}/items', 'DealController@productUpdate')->name('deal.items.update');
    Route::delete('deal/{id}/items/{uid}', 'DealController@productDestroy')->name('deal.items.destroy');

    Route::post('deal/{id}/file', 'DealController@fileUpload')->name('deal.file.upload');
    Route::get('deal/{id}/file/{fid}', 'DealController@fileDownload')->name('deal.file.download');
    Route::delete('deal/{id}/file/delete/{fid}', 'DealController@fileDelete')->name('deal.file.delete');


    Route::get('deal/{id}/task', 'DealController@taskCreate')->name('deal.tasks.create');
    Route::post('deal/{id}/task', 'DealController@taskStore')->name('deal.tasks.store');
    Route::get('deal/{id}/task/{tid}/show', 'DealController@taskShow')->name('deal.tasks.show');
    Route::get('deal/{id}/task/{tid}/edit', 'DealController@taskEdit')->name('deal.tasks.edit');
    Route::post('deal/{id}/task/{tid}', 'DealController@taskUpdate')->name('deal.tasks.update');
    Route::post('deal/{id}/task_status/{tid}', 'DealController@taskUpdateStatus')->name('deal.tasks.update_status');
    Route::delete('deal/{id}/task/{tid}', 'DealController@taskDestroy')->name('deal.tasks.destroy');

    Route::get('deal/{id}/products', 'DealController@productEdit')->name('deal.products.edit');
    Route::post('deal/{id}/products', 'DealController@productUpdate')->name('deal.products.update');
    Route::delete('deal/{id}/products/{uid}', 'DealController@productDestroy')->name('deal.products.destroy');

    Route::get('deal/{id}/sources', 'DealController@sourceEdit')->name('deal.sources.edit');
    Route::post('deal/{id}/sources', 'DealController@sourceUpdate')->name('deal.sources.update');
    Route::delete('deal/{id}/sources/{uid}', 'DealController@sourceDestroy')->name('deal.sources.destroy');

    Route::get('deal/{id}/discussions', 'DealController@discussionCreate')->name('deal.discussions.create');
    Route::post('deal/{id}/discussions', 'DealController@discussionStore')->name('deal.discussion.store');

    Route::get('deal/{id}/call', 'DealController@callCreate')->name('deal.call.create');
    Route::post('deal/{id}/call', 'DealController@callStore')->name('deal.call.store');
    Route::get('deal/{id}/call/{cid}/edit', 'DealController@callEdit')->name('deal.call.edit');
    Route::post('deal/{id}/call/{cid}', 'DealController@callUpdate')->name('deal.call.update');
    Route::delete('deal/{id}/call/{cid}', 'DealController@callDestroy')->name('deal.call.destroy');

    Route::get('deal/{id}/email', 'DealController@emailCreate')->name('deal.email.create');
    Route::post('deal/{id}/email', 'DealController@emailStore')->name('deal.email.store');

    Route::get('deal/{id}/clients', 'DealController@clientEdit')->name('deal.clients.edit');
    Route::post('deal/{id}/clients', 'DealController@clientUpdate')->name('deal.clients.update');
    Route::delete('deal/{id}/clients/{uid}', 'DealController@clientDestroy')->name('deal.clients.destroy');

    Route::get('deal/{id}/labels', 'DealController@labels')->name('deal.labels');
    Route::post('deal/{id}/labels', 'DealController@labelStore')->name('deal.labels.store');


    Route::get('deal/list', 'DealController@deal_list')->name('deal.list');
    Route::post('deal/change-pipeline', 'DealController@changePipeline')->name('deal.change.pipeline');

    Route::post('deal/change-deal-status/{id}', 'DealController@changeStatus')->name('deal.change.status')->middleware(
        [
            'auth',
            'XSS',
            'revalidate',
        ]
    );

    Route::resource('deal', 'DealController');
}
);

Route::post('deal/{id}/note', 'DealController@noteStore')->name('deal.note.store')->middleware(['auth']);

Route::group(
    [
        'middleware' => [
            'auth',
            'XSS',
            'revalidate',
        ],
    ], function (){
    Route::post('dealStage/order', 'DealStageController@order')->name('dealStage.order');
    Route::post('dealStage/json', 'DealStageController@json')->name('dealStage.json');
    Route::resource('dealStage', 'DealStageController');
}
);


Route::get('estimate/preview/{template}/{color}', 'EstimateController@previewEstimate')->name('estimate.preview');
Route::post('estimate/template/setting', 'EstimateController@saveEstimateTemplateSettings')->name('estimate.template.setting');
Route::get('estimate/pdf/{id}', 'EstimateController@pdf')->name('estimate.pdf')->middleware(['XSS']);
Route::group(
    [
        'middleware' => [
            'auth',
            'XSS',
            'revalidate',
        ],
    ], function (){
    Route::post('estimate/product/destroy', 'EstimateController@productDestroy')->name('estimate.product.destroy');
    Route::post('estimate/product', 'EstimateController@product')->name('estimate.product');
    Route::get('estimate/{id}/send', 'EstimateController@send')->name('estimate.send');
    Route::get('estimate/status', 'EstimateController@statusChange')->name('estimate.status.change');
    Route::get('estimate/items', 'EstimateController@items')->name('estimate.items');

    Route::get('estimate/{id}/convert', 'EstimateController@convert')->name('estimate.convert');
    Route::resource('estimate', 'EstimateController');
}
);


Route::group(
    [
        'middleware' => [
            'auth',
            'XSS',
            'revalidate',
        ],
    ], function (){

    Route::post('business-setting', 'SettingController@saveBusinessSettings')->name('business.setting');
    Route::post('company-setting', 'SettingController@saveCompanySettings')->name('company.setting');
    Route::post('email-setting', 'SettingController@saveEmailSettings')->name('email.setting');
    Route::post('system-setting', 'SettingController@saveSystemSettings')->name('system.setting');
    Route::post('pusher-setting', 'SettingController@savePusherSettings')->name('pusher.setting');
    Route::post('payment-setting', 'SettingController@savePaymentSettings')->name('payment.setting');
    Route::post('company-payment-setting', 'SettingController@saveCompanyPaymentSettings')->name('company.payment.setting');

    Route::get('test-mail', 'SettingController@testMail')->name('test.mail');
    Route::post('test-mail', 'SettingController@testSendMail')->name('test.send.mail');

    Route::get('settings', 'SettingController@index')->name('settings');
}
);

Route::group(
    [
        'middleware' => [
            'auth',
            'XSS',
            'revalidate',
        ],
    ], function (){

    Route::get('project/{project}/user', 'ProjectController@projectUser')->name('project.user');
    Route::post('project/{project}/user', 'ProjectController@addProjectUser')->name('project.user.add');
    Route::delete('project/{project}/user/{user}/destroy', 'ProjectController@destroyProjectUser')->name('project.user.destroy');
    Route::post('project/{project}/status', 'ProjectController@changeStatus')->name('project.status');
    Route::get('project/grid', 'ProjectController@grid')->name('project.grid');

    Route::get('project/{id}/task', 'ProjectController@taskBoard')->name('project.task');
    Route::get('project/{id}/task/create', 'ProjectController@taskCreate')->name('project.task.create');
    Route::post('project/{id}/task/store', 'ProjectController@taskStore')->name('project.task.store');
    Route::get('project/task/{id}/edit', 'ProjectController@taskEdit')->name('project.task.edit');
    Route::post('project/task/{id}/update', 'ProjectController@taskUpdate')->name('project.task.update');
    Route::delete('project/task/{id}/delete', 'ProjectController@taskDestroy')->name('project.task.destroy');
    Route::get('project/task/{id}/show', 'ProjectController@taskShow')->name('project.task.show');
    Route::post('project/order', 'ProjectController@order')->name('project.task.order');

    Route::post('project/task/{id}/checklist/store', 'ProjectController@checkListStore')->name('project.task.checklist.store');
    Route::post('project/task/{id}/checklist/{cid}/update', 'ProjectController@checklistUpdate')->name('project.task.checklist.update');
    Route::delete('project/task/{id}/checklist/{cid}', 'ProjectController@checklistDestroy')->name('project.task.checklist.destroy');

    Route::post('project/{id}/task/{tid}/comment', 'ProjectController@commentStore')->name('project.task.comment.store');
    Route::post('project/task/{id}/file', 'ProjectController@commentStoreFile')->name('project.task.comment.file.store');
    Route::delete('project/task/comment/{id}', 'ProjectController@commentDestroy')->name('project.task.comment.destroy');
    Route::delete('project/task/file/{id}', 'ProjectController@commentDestroyFile')->name('project.task.comment.file.destroy');

    Route::get('project/{id}/milestone', 'ProjectController@milestone')->name('project.milestone.create');
    Route::post('project/{id}/milestone', 'ProjectController@milestoneStore')->name('project.milestone.store');
    Route::get('project/milestone/{id}/edit', 'ProjectController@milestoneEdit')->name('project.milestone.edit');
    Route::post('project/milestone/{id}', 'ProjectController@milestoneUpdate')->name('project.milestone.update');
    Route::delete('project/milestone/{id}', 'ProjectController@milestoneDestroy')->name('project.milestone.destroy');
    Route::get('project/milestone/{id}/show', 'ProjectController@milestoneShow')->name('project.milestone.show');
    Route::get('project/task', 'ProjectController@task')->name('project.task');

    Route::get('project/{id}/note', 'ProjectController@notes')->name('project.note.create');
    Route::post('project/{id}/note', 'ProjectController@noteStore')->name('project.note.store');
    Route::get('project/{pid}/note/{id}', 'ProjectController@noteEdit')->name('project.note.edit');
    Route::post('project/{pid}/note/{id}', 'ProjectController@noteupdate')->name('project.note.update');
    Route::delete('project/{pid}/note/{id}', 'ProjectController@noteDestroy')->name('project.note.destroy');

    Route::get('project/{id}/file', 'ProjectController@file')->name('project.file.create');
    Route::post('project/{id}/file', 'ProjectController@fileStore')->name('project.file.store');
    Route::get('project/{pid}/file/{id}', 'ProjectController@fileEdit')->name('project.file.edit');
    Route::post('project/{pid}/file/{id}', 'ProjectController@fileupdate')->name('project.file.update');
    Route::delete('project/{pid}/file/{id}', 'ProjectController@fileDestroy')->name('project.file.destroy');


    Route::post('project/{id}/comment', 'ProjectController@projectCommentStore')->name('project.comment.store');
    Route::get('project/{id}/comment', 'ProjectController@projectComment')->name('project.comment.create');
    Route::get('project/{id}/comment/{cid}/reply', 'ProjectController@projectCommentReply')->name('project.comment.reply');


    Route::post('project/{id}/client/feedback', 'ProjectController@projectClientFeedbackStore')->name('project.client.feedback.store');
    Route::get('project/{id}/client/feedback', 'ProjectController@projectClientFeedback')->name('project.client.feedback.create');
    Route::get('project/{id}/client/feedback/{cid}/reply', 'ProjectController@projectClientFeedbackReply')->name('project.client.feedback.reply');


    Route::get('project/{id}/timesheet', 'ProjectController@projectTimesheet')->name('project.timesheet.create');
    Route::post('project/{id}/timesheet', 'ProjectController@projectTimesheetStore')->name('project.timesheet.store');
    Route::get('project/{id}/timesheet/{tid}/edit', 'ProjectController@projectTimesheetEdit')->name('project.timesheet.edit');
    Route::post('project/{id}/timesheet{tid}/edit', 'ProjectController@projectTimesheetUpdate')->name('project.timesheet.update');
    Route::delete('project/{pid}/timesheet/{id}', 'ProjectController@projectTimesheetDestroy')->name('project.timesheet.destroy');
    Route::get('project/{id}/timesheet/{tid}/note', 'ProjectController@projectTimesheetNote')->name('project.timesheet.note');


    Route::get('project/timesheet', 'ProjectController@timesheet')->name('project.timesheet');

    //    For Project All Task
    Route::get('project/allTask', 'ProjectController@allTask')->name('project.all.task');
    Route::get('project/allTaskKanban', 'ProjectController@allTaskKanban')->name('project.all.task.kanban');
    Route::get('project/alltask/gantt-chart/{duration?}', 'ProjectController@allTaskGanttChart')->name('project.all.task.gantt.chart');
    Route::post('project/milestone', 'ProjectController@getMilestone')->name('project.getMilestone');
    Route::post('project/user', 'ProjectController@getUser')->name('project.getUser');

    //    For Project All Task
    Route::get('project/allTimesheet', 'ProjectController@allTimesheet')->name('project.all.timesheet');
    Route::post('project/task', 'ProjectController@getTask')->name('project.getTask');

    // Gantt Chart

    Route::post('projects/{id}/gantt', 'ProjectController@ganttPost')->name('project.gantt.post')->middleware(
        [
            'auth',
            'XSS',
            'revalidate',
        ]
    );

    // Project Task Timer
    Route::post('project/task/timer', 'ProjectController@taskStart')->name('project.task.timer');
    Route::resource('project', 'ProjectController')->middleware(
        [
            'auth',
            'XSS',
            'revalidate',
        ]
    );
}
);

Route::group(
    [
        'middleware' => [
            'auth',
            'XSS',
            'revalidate',
        ],
    ], function (){
    Route::resource('projectStage', 'ProjectStageController');
    Route::post('projectStage/order', 'ProjectStageController@order')->name('projectStage.order');
}
);

Route::resource('payment', 'PaymentController')->middleware(
    [
        'auth',
        'XSS',
        'revalidate',
    ]
);

Route::group(
    [
        'middleware' => [
            'auth',
            'XSS',
            'revalidate',
        ],
    ], function (){

    Route::get('creditNote/invoice', 'CreditNoteController@getinvoice')->name('invoice.get');
    Route::resource('creditNote', 'CreditNoteController');
}
);
Route::group(
    [
        'middleware' => [
            'auth',
            'XSS',
            'revalidate',
        ],
    ], function (){
    Route::resource('expense', 'ExpenseController');
}
);

Route::group(
    [
        'middleware' => [
            'auth',
            'XSS',
            'revalidate',
        ],
    ], function (){
    Route::get('contract/{id}/description', 'ContractController@description')->name('contract.description');
    Route::get('contract/grid', 'ContractController@grid')->name('contract.grid');
    Route::resource('contract', 'ContractController');
}
);

Route::group(
    [
        'middleware' => [
            'auth',
            'XSS',
            'revalidate',
        ],
    ], function (){
    Route::resource('contractType', 'ContractTypeController');
}
);

Route::get('noticeBoard/grid', 'NoticeBoardController@grid')->name('noticeBoard.grid');
Route::resource('noticeBoard', 'NoticeBoardController')->middleware(
    [
        'auth',
        'XSS',
        'revalidate',
    ]
);

Route::group(
    [
        'middleware' => [
            'auth',
            'XSS',
            'revalidate',
        ],
    ], function (){
    Route::resource('goal', 'GoalController');
}
);

Route::group(
    [
        'middleware' => [
            'auth',
            'XSS',
            'revalidate',
        ],
    ], function (){
    Route::resource('note', 'NoteController');
}
);

Route::group(
    [
        'middleware' => [
            'auth',
            'XSS',
            'revalidate',
        ],
    ], function (){
    Route::post('event/employee', 'EventController@getEmployee')->name('event.employee');
    Route::resource('event', 'EventController');
}
);

Route::group(
    [
        'middleware' => [
            'auth',
            'XSS',
            'revalidate',
        ],
    ], function (){
    Route::get('support/{id}/reply', 'SupportController@reply')->name('support.reply');
    Route::post('support/{id}/reply', 'SupportController@replyAnswer')->name('support.reply.answer');
    Route::get('support/grid', 'SupportController@grid')->name('support.grid');
    Route::resource('support', 'SupportController');
}
);



Route::post('change-password', 'UserController@updatePassword')->name('update.password');

Route::get(
    '/change/mode', [
                      'as' => 'change.mode',
                      'uses' => 'UserController@changeMode',
                  ]
);

//========================================HR===============================

Route::resource('account-assets', 'AssetController')->middleware(
    [
        'auth',
        'XSS',
        'revalidate',
    ]
);

Route::resource('document-upload', 'DocumentUploadController')->middleware(
    [
        'auth',
        'XSS',
        'revalidate',
    ]
);

Route::resource('company-policy', 'CompanyPolicyController')->middleware(
    [
        'auth',
        'XSS',
        'revalidate',
    ]
);

Route::resource('award', 'AwardController')->middleware(
    [
        'auth',
        'XSS',
        'revalidate',
    ]
);

Route::resource('transfer', 'TransferController')->middleware(
    [
        'auth',
        'XSS',
        'revalidate',
    ]
);

Route::resource('award-type', 'AwardTypeController')->middleware(
    [
        'auth',
        'XSS',
        'revalidate',
    ]
);
Route::resource('resignation', 'ResignationController')->middleware(
    [
        'auth',
        'XSS',
        'revalidate',
    ]
);

Route::resource('trip', 'TripController')->middleware(
    [
        'auth',
        'XSS',
        'revalidate',
    ]
);
Route::resource('promotion', 'PromotionController')->middleware(
    [
        'auth',
        'XSS',
        'revalidate',
    ]
);

Route::resource('complaint', 'ComplaintController')->middleware(
    [
        'auth',
        'XSS',
        'revalidate',
    ]
);

Route::resource('warning', 'WarningController')->middleware(
    [
        'auth',
        'XSS',
        'revalidate',
    ]
);
Route::resource('termination', 'TerminationController')->middleware(
    [
        'auth',
        'XSS',
        'revalidate',
    ]
);
Route::resource('termination-type', 'TerminationTypeController')->middleware(
    [
        'auth',
        'XSS',
        'revalidate',
    ]
);

Route::resource('indicator', 'IndicatorController')->middleware(
    [
        'auth',
        'XSS',
        'revalidate',
    ]
);
Route::post('employee/json', 'EmployeeController@json')->name('employee.json')->middleware(
    [
        'auth',
        'XSS',
        'revalidate',
    ]
);
Route::resource('appraisal', 'AppraisalController')->middleware(
    [
        'auth',
        'XSS',
        'revalidate',
    ]
);

Route::resource('training-type', 'TrainingTypeController')->middleware(
    [
        'auth',
        'XSS',
        'revalidate',
    ]
);

Route::resource('trainer', 'TrainerController')->middleware(
    [
        'auth',
        'XSS',
        'revalidate',
    ]
);

Route::post('training/status', 'TrainingController@updateStatus')->name('training.status')->middleware(
    [
        'auth',
        'XSS',
        'revalidate',
    ]
);
Route::resource('training', 'TrainingController')->middleware(
    [
        'auth',
        'XSS',
        'revalidate',
    ]
);


//========================================OLD===============================
Route::get('profile', 'UserController@profile')->name('profile')->middleware(
    [
        'auth',
        'XSS',
        'revalidate',
    ]
);

Route::post('edit-profile', 'UserController@editprofile')->name('update.account')->middleware(
    [
        'auth',
        'XSS',
        'revalidate',
    ]
);
Route::post('edit-client-profile/{id}', 'UserController@clientCompanyInfoEdit')->name('client.update.company')->middleware(
    [
        'auth',
        'XSS',
        'revalidate',
    ]
);
Route::post('edit-client-personal-info/{id}', 'UserController@clientPersonalInfoEdit')->name('client.personal.update')->middleware(
    [
        'auth',
        'XSS',
        'revalidate',
    ]
);


Route::resource('users', 'UserController')->middleware(
    [
        'auth',
        'XSS',
        'revalidate',
    ]
);


Route::group(
    [
        'middleware' => [
            'auth',
            'XSS',
            'revalidate',
        ],
    ], function (){
    Route::resource('unit', 'UnitController');
}
);
Route::group(
    [
        'middleware' => [
            'auth',
            'XSS',
            'revalidate',
        ],
    ], function (){
    Route::resource('paymentMethod', 'PaymentMethodController');
}
);


Route::group(
    [
        'middleware' => [
            'auth',
            'XSS',
            'revalidate',
        ],
    ], function (){
    Route::resource('user', 'UserController');
}
);

Route::group(
    [
        'middleware' => [
            'auth',
            'XSS',
            'revalidate',
        ],
    ], function (){
    Route::get('item/grid', 'ItemController@grid')->name('item.grid');
    Route::resource('item', 'ItemController');
}
);
Route::group(
    [
        'middleware' => [
            'auth',
            'XSS',
            'revalidate',
        ],
    ], function (){
    Route::resource('proposal', 'ProposalController');
}
);


Route::get('invoice/preview/{template}/{color}', 'InvoiceController@previewInvoice')->name('invoice.preview');
Route::post('invoice/template/setting', 'InvoiceController@saveInvoiceTemplateSettings')->name('invoice.template.setting');
Route::get('invoice/pdf/{id}', 'InvoiceController@pdf')->name('invoice.pdf')->middleware(['XSS']);

Route::group(
    [
        'middleware' => [
            'auth',
            'XSS',
            'revalidate',
        ],
    ], function (){
    Route::post('invoice/client/project', 'InvoiceController@getClientProject')->name('invoice.client.project');
    Route::get('invoice/{id}/item/create', 'InvoiceController@createItem')->name('invoice.create.item');
    Route::post('invoice/{id}/product/store', 'InvoiceController@storeProduct')->name('invoice.store.product');
    Route::post('invoice/{id}/project/store', 'InvoiceController@storeProject')->name('invoice.store.project');
    Route::get('invoice/{id}/send', 'InvoiceController@send')->name('invoice.send');
    Route::get('invoice/{id}/receipt/create', 'InvoiceController@createReceipt')->name('invoice.create.receipt');
    Route::post('invoice/{id}/receipt/store', 'InvoiceController@storeReceipt')->name('invoice.store.receipt');
    Route::delete('invoice/{id}/payment/{pid}', 'InvoiceController@paymentDelete')->name('invoice.payment.delete');
    Route::get('invoice/status', 'InvoiceController@statusChange')->name('invoice.status.change');

    Route::get('invoice/item', 'InvoiceController@items')->name('invoice.items');
    Route::delete('invoice/{id}/item/{pid}', 'InvoiceController@itemDelete')->name('invoice.item.delete');
    Route::get('invoice/grid', 'InvoiceController@grid')->name('invoice.grid');

    Route::resource('invoice', 'InvoiceController');
}
);


Route::group(
    [
        'middleware' => [
            'auth',
            'XSS',
            'revalidate',
        ],
    ], function (){
    Route::get('task-report', 'ReportController@task')->name('report.task');
    Route::get('timelog-report', 'ReportController@timelog')->name('report.timelog');
    Route::get('finance-report', 'ReportController@finance')->name('report.finance');
    Route::get('income-expense-report', 'ReportController@incomeVsExpense')->name('report.income.expense');
    Route::get('leave-report', 'ReportController@leave')->name('report.leave');
    Route::get('stock-report', 'ReportController@productStock')->name('report.product.stock.report');

    Route::get('employee/{id}/leave/{status}/{type}/{month}/{year}', 'ReportController@employeeLeave')->name('report.employee.leave')->middleware(
        [
            'auth',
            'XSS',
            'revalidate',
        ]
    );


    //add module double entry
    Route::get('report/ledger', 'ReportController@ledgerSummary')->name('report.ledger');
    Route::get('report/balance-sheet', 'ReportController@balanceSheet')->name('report.balance.sheet');
    Route::get('report/trial-balance', 'ReportController@trialBalanceSummary')->name('trial.balance');



    Route::get('estimate-report', 'ReportController@estimate')->name('report.estimate');
    Route::get('invoice-report', 'ReportController@invoice')->name('report.invoice');
    Route::get('lead-report', 'ReportController@lead')->name('report.lead');
    Route::get('client-report', 'ReportController@client')->name('report.client');
    Route::get('attendance-report', 'ReportController@attendance')->name('report.attendance');
}
);

Route::get('report/attendance/{month}/{department}', 'ReportController@exportCsv')->name('report.attendance.monthly')->middleware(
    [
        'auth',
        'XSS',
    ]
);

Route::group(
    [
        'middleware' => [
            'auth',
            'XSS',
            'revalidate',
        ],
    ], function (){
    Route::get('change-language/{lang}', 'LanguageController@changeLanquage')->name('change.language')->middleware(
        [
            'auth',
            'XSS',
            'revalidate',
        ]
    );
    Route::get('manage-language/{lang}', 'LanguageController@manageLanguage')->name('manage.language')->middleware(
        [
            'auth',
            'XSS',
            'revalidate',
        ]
    );
    Route::post('store-language-data/{lang}', 'LanguageController@storeLanguageData')->name('store.language.data')->middleware(
        [
            'auth',
            'XSS',
            'revalidate',
        ]
    );
    Route::get('create-language', 'LanguageController@createLanguage')->name('create.language')->middleware(
        [
            'auth',
            'XSS',
            'revalidate',
        ]
    );
    Route::post('store-language', 'LanguageController@storeLanguage')->name('store.language')->middleware(
        [
            'auth',
            'XSS',
            'revalidate',
        ]
    );

    Route::delete('/lang/{lang}', 'LanguageController@destroyLang')->name('lang.destroy')->middleware(
        [
            'auth',
            'XSS',
            'revalidate',
        ]
    );
}
);


// Email Templates
Route::get('email_template_lang/{id}/{lang?}', 'EmailTemplateController@manageEmailLang')->name('manage.email.language')->middleware(['auth']);
Route::post('email_template_store/{pid}', 'EmailTemplateController@storeEmailLang')->name('store.email.language')->middleware(['auth']);
Route::post('email_template_status/{id}', 'EmailTemplateController@updateStatus')->name('status.email.language')->middleware(['auth']);

Route::resource('email_template', 'EmailTemplateController')->middleware(
    [
        'auth',
        'XSS',
        'revalidate',
    ]
);
Route::resource('email_template_lang', 'EmailTemplateLangController')->middleware(
    [
        'auth',
        'XSS',
        'revalidate',
    ]
);



// Form Builder
Route::resource('form_builder', 'FormBuilderController')->middleware(
    [
        'auth',
        'XSS',
    ]
);

// Form link base view
Route::get('/form/{code}', 'FormBuilderController@formView')->name('form.view')->middleware(['XSS']);
Route::post('/form_view_store', 'FormBuilderController@formViewStore')->name('form.view.store')->middleware(['XSS']);

// Form Field
Route::get('/form_builder/{id}/field', 'FormBuilderController@fieldCreate')->name('form.field.create')->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::post('/form_builder/{id}/field', 'FormBuilderController@fieldStore')->name('form.field.store')->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::get('/form_builder/{id}/field/{fid}/show', 'FormBuilderController@fieldShow')->name('form.field.show')->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::get('/form_builder/{id}/field/{fid}/edit', 'FormBuilderController@fieldEdit')->name('form.field.edit')->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::post('/form_builder/{id}/field/{fid}', 'FormBuilderController@fieldUpdate')->name('form.field.update')->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::delete('/form_builder/{id}/field/{fid}', 'FormBuilderController@fieldDestroy')->name('form.field.destroy')->middleware(
    [
        'auth',
        'XSS',
    ]
);

// Form Response
Route::get('/form_response/{id}', 'FormBuilderController@viewResponse')->name('form.response')->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::get('/response/{id}', 'FormBuilderController@responseDetail')->name('response.detail')->middleware(
    [
        'auth',
        'XSS',
    ]
);

// Form Field Bind
Route::get('/form_field/{id}', 'FormBuilderController@formFieldBind')->name('form.field.bind')->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::post('/form_field_store/{id}', 'FormBuilderController@bindStore')->name('form.bind.store')->middleware(
    [
        'auth',
        'XSS',
    ]
);
// end Form Builder



//================================= Custom Landing Page ====================================//

Route::get('/landingpage', 'LandingPageSectionController@index')->name('custom_landing_page.index')->middleware(['auth','XSS']);
Route::get('/LandingPage/show/{id}', 'LandingPageSectionController@show');
Route::post('/LandingPage/setConetent', 'LandingPageSectionController@setConetent')->middleware(['auth','XSS']);
Route::get('/get_landing_page_section/{name}', function($name) {
    return view('custom_landing_page.'.$name);
});
Route::post('/LandingPage/removeSection/{id}', 'LandingPageSectionController@removeSection')->middleware(['auth','XSS']);
Route::post('/LandingPage/setOrder', 'LandingPageSectionController@setOrder')->middleware(['auth','XSS']);
Route::post('/LandingPage/copySection', 'LandingPageSectionController@copySection')->middleware(['auth','XSS']);




//================================= Invoice Payment Gateways  ====================================//

Route::get('/plan/coingate/{plan}', ['as' => 'plan.coingate','uses' => 'CoingatePaymentController@getPaymentStatus']);
Route::post('{id}/pay-with-paypal', 'PaypalController@clientPayWithPaypal')->name('client.pay.with.paypal')->middleware(
    [
        
        'XSS',
        'revalidate',
    ]
);
Route::get('{id}/get-payment-status', 'PaypalController@clientGetPaymentStatus')->name('client.get.payment.status')->middleware(
    [
       
        'XSS',
        'revalidate',
    ]
);

Route::post('invoice/{id}/payment', 'StripePaymentController@addpayment')->name('client.invoice.payment')->middleware(
    [
       
        'XSS',
        'revalidate',
    ]
);


Route::post('/invoice-pay-with-paystack',['as' => 'invoice.pay.with.paystack','uses' =>'PaystackPaymentController@invoicePayWithPaystack'])->middleware(['XSS']);
Route::get('/invoice/paystack/{pay_id}/{invoice_id}', ['as' => 'invoice.paystack','uses' => 'PaystackPaymentController@getInvoicePaymentStatus']);

Route::post('/invoice-pay-with-flaterwave',['as' => 'invoice.pay.with.flaterwave','uses' =>'FlutterwavePaymentController@invoicePayWithFlutterwave'])->middleware(['XSS']);
Route::get('/invoice/flaterwave/{invoice_id}/{pay_id}/{amount}', ['as' => 'invoice.flaterwave','uses' => 'FlutterwavePaymentController@getInvoicePaymentStatus']);


Route::any('/invoice-pay-with-razorpay',['as' => 'invoice.pay.with.razorpay','uses' =>'RazorpayPaymentController@invoicePayWithRazorpay'])->middleware(['XSS']);
Route::get('/invoice/razorpay/{txref}/{invoice_id}', ['as' => 'invoice.razorpay','uses' => 'RazorpayPaymentController@getInvoicePaymentStatus']);

Route::post('/invoice-pay-with-paytm',['as' => 'invoice.pay.with.paytm','uses' =>'PaytmPaymentController@invoicePayWithPaytm'])->middleware(['XSS']);
Route::post('/invoice/paytm/{invoice}/{amount}', ['as' => 'invoice.paytm','uses' => 'PaytmPaymentController@getInvoicePaymentStatus']);

Route::post('/invoice-pay-with-mercado',['as' => 'invoice.pay.with.mercado','uses' =>'MercadoPaymentController@invoicePayWithMercado'])->middleware(['XSS']);
Route::any('/invoice/mercado/{invoice}', ['as' => 'invoice.mercado','uses' => 'MercadoPaymentController@getInvoicePaymentStatus']);

Route::post('/invoice-pay-with-mollie',['as' => 'invoice.pay.with.mollie','uses' =>'MolliePaymentController@invoicePayWithMollie'])->middleware(['XSS']);
Route::get('/invoice/mollie/{invoice}/{amount}', ['as' => 'invoice.mollie','uses' => 'MolliePaymentController@getInvoicePaymentStatus']);

Route::post('/invoice-pay-with-skrill',['as' => 'invoice.pay.with.skrill','uses' =>'SkrillPaymentController@invoicePayWithSkrill'])->middleware(['XSS']);
Route::get('/invoice/skrill/{invoice}/{amount}', ['as' => 'invoice.skrill','uses' => 'SkrillPaymentController@getInvoicePaymentStatus']);

Route::post('/invoice-pay-with-coingate',['as' => 'invoice.pay.with.coingate','uses' =>'CoingatePaymentController@invoicePayWithCoingate'])->middleware(['XSS']);
Route::get('/invoice/coingate/{invoice}/{amount}', ['as' => 'invoice.coingate','uses' => 'CoingatePaymentController@getInvoicePaymentStatus']);

Route::post('/paymentwall' , ['as' => 'invoice.paymentwallpayment','uses' =>'PaymentWallPaymentController@invoicepaymentwall'])->middleware(['XSS']);
Route::post('/invoice-pay-with-paymentwall/{plan}',['as' => 'invoice.pay.with.paymentwall','uses' =>'PaymentWallPaymentController@invoicePayWithPaymentwall'])->middleware(['XSS']);
Route::get('/invoices/{flag}/{invoice}', ['as' => 'error.invoice.show','uses' => 'PaymentWallPaymentController@invoiceerror']);


//====================================Competencies=================================================================//
Route::resource('competencies', 'CompetenciesController')->middleware(
    [
        'auth',
        'XSS',
    ]
);

Route::resource('performanceType', 'PerformanceTypeController')->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::post('chart-of-account/subtype', 'ChartOfAccountController@getSubType')->name('charofAccount.subType')->middleware(
    [
        'auth',
        'XSS','revalidate',
    ]
);

Route::group(
    [
        'middleware' => [
            'auth',
            'XSS','revalidate',
        ],
    ], function (){

    Route::resource('chart-of-account', 'ChartOfAccountController');

}
);



Route::group(
    [
        'middleware' => [
            'auth',
            'XSS','revalidate',
        ],
    ], function (){

    Route::post('journal-entry/account/destroy', 'JournalEntryController@accountDestroy')->name('journal.account.destroy');
    Route::resource('journal-entry', 'JournalEntryController');

}
);

//Goal Tracking

Route::resource('goaltracking', 'GoalTrackingController')->middleware(
    [
        'auth',
        'XSS',
    ]
);


Route::resource('branch', 'BranchController')->middleware(
    [
        'auth',
        'XSS',
    ]
);

Route::resource('goaltype', 'GoalTypeController')->middleware(
    [
        'auth',
        'XSS',
    ]
);

//Invoice Copy Button

Route::get(
    '/invoice/pay/{invoice}', [
           'as' => 'pay.invoice',
           'uses' => 'InvoiceController@payinvoice',
       ]
);

Route::get('invoice/pay/pdf/{id}', 'InvoiceController@pdffrominvoice')->name('invoice.download.pdf');

Route::post('/invoice-pay-with-stripe',['as' => 'invoice.pay.with.stripe','uses' =>'StripePaymentController@invoicePayWithStripe']);


Route::get(
    '/estimate/pay/{estimate}', [
           'as' => 'pay.estimate',
           'uses' => 'EstimateController@payestimate',
       ]
);

Route::get('estimate/pay/pdf/{id}', 'EstimateController@pdffromestimate')->name('estimate.download.pdf');

Route::any('/invoice-pay-with-stripe/{invoice_id}/{pay_id}','StripePaymentController@getInvociePaymentStatus')->name('invoice.stripe')->middleware(['XSS']);


//Import/Export 

Route::get('import/employee/file', 'EmployeeController@importFile')->name('employee.file.import')->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::post('import/employee', 'EmployeeController@import')->name('employee.import')->middleware(
    [
        'auth',
        'XSS',
    ]
);

Route::get('import/client/file', 'ClientController@importFile')->name('client.file.import')->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::post('import/client', 'ClientController@import')->name('client.import')->middleware(
    [
        'auth',
        'XSS',
    ]
);

Route::get('import/holiday/file', 'HolidayController@importFile')->name('holiday.file.import')->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::post('import/holiday', 'HolidayController@import')->name('holiday.import')->middleware(
    [
        'auth',
        'XSS',
    ]
);

Route::get('import/assets/file', 'AssetController@importFile')->name('assets.file.import')->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::post('import/assets', 'AssetController@import')->name('assets.import')->middleware(
    [
        'auth',
        'XSS',
    ]
);

Route::get('export/item', 'ItemController@export')->name('item.export');
Route::get('import/asset/file', 'ItemController@importFile')->name('item.file.import')->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::post('import/item', 'ItemController@import')->name('item.import')->middleware(
    [
        'auth',
        'XSS',
    ]
);

Route::get('export/meeting', 'MeetingController@export')->name('meeting.export');

Route::get('export/award', 'AwardController@export')->name('award.export');

Route::get('export/invoice', 'InvoiceController@export')->name('invoice.export');

Route::get('export/creditnote', 'CreditNoteController@export')->name('creditnote.export');

Route::get('export/goal', 'GoalController@export')->name('goal.export');

//================================Budget Plan======================================//

Route::resource('budget', 'BudgetController')->middleware(
    [
        'auth',
        'XSS',
    ]
);

//================================Time Tracker======================================//

Route::resource('timetracker', 'TimeTrackerController')->middleware(
    [
        'auth',
        'XSS',
    ]
);

Route::post('tracker/image-view', ['as' => 'tracker.image.view','uses' => 'TimeTrackerController@getTrackerImages']);
Route::delete('tracker/image-remove', ['as' => 'tracker.image.remove','uses' => 'TimeTrackerController@removeTrackerImages']);


//==============================Zoom Meeting ========================================//
Route::any('/setting/saveZoomSettings', ['as' => 'setting.ZoomSettings','uses' => 'SettingController@saveZoomSettings'])->middleware(['auth','XSS']);
// Route::get('zoommeeting/calendar', 'ZoommeetingController@calendar')->name('zoommeeting.calendar');
// Route::resource('zoommeeting', 'ZoommeetingController')->middleware(
//     [
//         'auth',
//         'XSS',
//     ]
// );

Route::group(
    [
        'middleware' => [
            'auth',
            'XSS',
            'revalidate',
        ],
    ], function (){
    Route::get('zoommeeting/calendar', 'ZoommeetingController@calendar')->name('zoommeeting.calendar');
    Route::resource('zoommeeting', 'ZoommeetingController');
}
);


Route::get('/zoom/project/select/{id}', 'ZoommeetingController@projectwiseuser')->name('zoom.project.select');
Route::post('setting/slack','SettingController@slack')->name('slack.setting');

//==============================telegram===============================

Route::post('setting/telegram','SettingController@telegram')->name('telegram.setting');

//==============================twilio===============================

Route::post('setting/twilio','SettingController@twilio')->name('twilio.setting');

//==================================Recaptcha================================
Route::post('/recaptcha-settings',['as' => 'recaptcha.settings.store','uses' =>'SettingController@recaptchaSettingStore'])->middleware(['auth','XSS']);

Route::get('{image}/payment/attachment/{extention}',['as' => 'payment.receipt','uses' =>'PaymentController@download']);

Route::get('{image}/invoice/attachment/{extention}',['as' => 'invoice.receipt','uses' =>'InvoiceController@download']);

Route::get('{image}/expense/attachment/{extention}',['as' => 'expense.receipt','uses' =>'ExpenseController@download']);

Route::get('{image}/support/attachment/{extention}',['as' => 'support.receipt','uses' =>'SupportController@download']);

Route::get('{image}/note/attachment/{extention}',['as' => 'note.receipt','uses' =>'NoteController@download']);

//==========================ItemStock===================================//
Route::resource('itemstock', 'ItemStockController');
