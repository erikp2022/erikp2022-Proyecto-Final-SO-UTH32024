<?php

use App\Http\Controllers\Admin\LanguageController;
use App\Http\Controllers\Admin\LanguageTranslationController;
use App\Http\Controllers\Admin\StaffManageController;
use App\Http\Controllers\Admin\CustomFieldController;
use App\Http\Controllers\Admin\EmailTemplateController;
use App\Http\Controllers\Admin\GeneralSettingController;
use App\Http\Controllers\Admin\HowWorkController;
use App\Http\Controllers\Admin\InboxContactController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\TestimonialController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\CommentsController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DepartmentsController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\KnowledgeBaseController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\SwitchLanguageController;
use App\Http\Controllers\TicketsController;
use App\Http\Controllers\VoteController;
use Illuminate\Support\Facades\Route;


Route::get('/getlangdir', [SwitchLanguageController::class,'getLanguage']);
Route::get('/switchLang/{lang}', [SwitchLanguageController::class,'switchLang'])->name('switch.language');

//Route::group(['middleware' => ['installChecker', 'locale']], function () {
Route::group(['middleware' => ['installChecker']], function () {
    // Authentication Routes...
    Route::get('login', [LoginController::class,'showLoginForm'])->name('login');
    Route::post('login', [LoginController::class,'login']);

    // Registration Routes...
    Route::get('register', [RegisterController::class,'showRegistrationForm'])->name('register');
    Route::post('register', [RegisterController::class,'register']);
});

Route::group(['middleware' => ['installChecker', 'locale','prevent-back-history']], function () {
    Route::get('/', [HomeController::class,'index'])->name('homePage');
    Route::get('contact-us', [ContactController::class,'index'])->name('contactPage');
    Route::post('contact-store', [ContactController::class,'store'])->name('contactStore');

    Route::get('/knowledge', [KnowledgeBaseController::class, 'KnowledgeBaseIndex'])->name('KnowledgeBaseIndex');
    Route::get('/knowledge/{id}.details', [KnowledgeBaseController::class, 'viewArticle'])->name('Knowledge.viewArticle');
    Route::post('/knowledge-search', [KnowledgeBaseController::class, 'searchArticles'])->name('Knowledge.searchArticles');
    Route::get('/knowledge-pinned/{id}', [KnowledgeBaseController::class, 'pinnedArticle'])->name('Knowledge.pinnedArticle');
    Route::get('/category/{category}', [KnowledgeBaseController::class, 'categoryPost'])->name('Knowledge.categoryPost');
    Route::post('/kb-vote/{id}', [VoteController::class, 'KBvoteYes'])->name('KBvoteYes');

    //Auth::routes();
    Route::get('password/reset/{token}', [ResetPasswordController::class, 'resetForm']);

    Route::get('password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('passwordRequest');
    Route::post('reset-password', [ForgotPasswordController::class, 'recoverResetLinkEmail'])->name('passwordReset');

    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::get('/about-us', [HomeController::class, 'aboutusPage'])->name('aboutusPage');

    // Password Reset Routes...
    Route::get('password/reset/{token}', [ResetPasswordController::class, 'showResetForm']);
    Route::post('password/reset', [ResetPasswordController::class, 'reset'])->name('password.request');


    Route::resource('email-template', EmailTemplateController::class);
});

Route::group(['middleware' => ['auth', 'installChecker', 'locale']], function () {
    Route::post('logout', [LoginController::class, 'logout'])->name('logout');
    Route::get('/logout', [LoginController::class, 'logout'])->name('logout');
});

Route::group(['middleware' => ['auth', 'installChecker', 'locale', 'prevent-back-history']], function () {

    //dashboard
    Route::get('dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');

    Route::get('count', [NotificationController::class, 'count']);
    Route::get('countup/{id}', [NotificationController::class, 'countUp']);

    //tickets route
    Route::get('add-new-ticket', [TicketsController::class, 'create'])->name('submit-new-ticket.create');
    Route::post('new-ticket-store', [TicketsController::class, 'store'])->name('new-ticket-store.store');
    Route::get('ticket/{ticket_id}', [TicketsController::class, 'show'])->name('ticket.show');
    Route::get('tickets', [TicketsController::class, 'index'])->name('tickets.index');
    Route::get('opened-tickets', [TicketsController::class, 'openedTickets'])->name('opened-tickets.openedTickets');
    Route::get('closed-tickets', [TicketsController::class, 'ClosedTickets'])->name('closed-tickets.ClosedTickets');
    Route::post('close_ticket/{ticket_id}', [TicketsController::class, 'close'])->name('close_ticket.close');
    Route::post('reopen/{ticket_id}', [TicketsController::class, 'reOpen'])->name('ticketReOpen');
    Route::get('notifications', [NotificationController::class, 'allNotification'])->name('allNotification');
    //ticket data
    Route::get('get-ticket-data', [TicketsController::class, 'getTicketData'])->name('getTicketData');
    Route::get('ticket-assign-to/{id}', [TicketsController::class, 'assignTo'])->name('assignTo');
    Route::post('ticket-assigned/{id}', [TicketsController::class, 'assignToDepartment'])->name('assignToDepartment');

    Route::post('comment', [CommentsController::class, 'postComment'])->name('comment.postComment');
    //staff route
    Route::get('staffs', [StaffManageController::class, 'staffList'])->name('staffs.staffList');
    Route::get('staff-edit/{id}', [StaffManageController::class, 'staffEdit'])->name('staff-edit.staffEdit');
    Route::post('staff-update/{id}', [StaffManageController::class, 'staffUpdate'])->name('staff-update.staffUpdate');
    Route::get('add-staff', [StaffManageController::class, 'createStaff'])->name('add-staff.createStaff');
    Route::post('save-staff', [StaffManageController::class, 'saveStaff'])->name('save-staff.saveStaff');
    Route::post('staff-status/{id}', [StaffManageController::class, 'action'])->name('staff-status.action');

    //department route
    Route::get('/departments', [DepartmentsController::class, 'index'])->name('departments.index');
    Route::get('get-departments-data', [DepartmentsController::class, 'getDepartmentData'])->name('getDepartmentData');
    Route::get('/department-create', [DepartmentsController::class, 'create'])->name('department-create.create');
    Route::get('/department/{id}/edit', [DepartmentsController::class, 'edit'])->name('department-edit');

    Route::get('department', [DepartmentsController::class, 'index'])->name('department.index');
    Route::get('department/{id}', [DepartmentsController::class, 'departmentTickets'])->name('departmentTickets');

    Route::post('department-save', [DepartmentsController::class, 'store'])->name('department-save.store');
    Route::get('department-edit/{id}', [DepartmentsController::class, 'edit'])->name('department-edit.edit');
    Route::post('department-update/{id}', [DepartmentsController::class, 'update'])->name('department-update.update');
    Route::delete('department-delete/{id}', [DepartmentsController::class, 'destroy'])->name('department-delete.destroy');
    //route knowledge
    Route::get('knowledge-base', [KnowledgeBaseController::class, 'index'])->name('knowledge-base.index');
    Route::get('knowledge-base-create', [KnowledgeBaseController::class, 'create'])->name('knowledge-base-create.create');
    Route::post('kb-store', [KnowledgeBaseController::class, 'store'])->name('kb.store');
    Route::get('knowledge-base-edit/{id}', [KnowledgeBaseController::class, 'edit'])->name('knowledge-base-edit.edit');
    Route::post('kb-update/{id}', [KnowledgeBaseController::class, 'update'])->name('kb.update');
    Route::delete('kb-destroy/{id}', [KnowledgeBaseController::class, 'destroy'])->name('kb.destroy');

    //profile route
    Route::get('profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::put('profile-update', [ProfileController::class, 'profileUpdate'])->name('profileUpdate');
    Route::post('changed-password', [ProfileController::class, 'changedPassword'])->name('changed-password.changedPassword');

    //roles route
    Route::get('roles', [RoleController::class, 'index'])->name('roles.index');
    Route::get('role-create', [RoleController::class, 'create'])->name('role-create.create');
    Route::post('role-save', [RoleController::class, 'store'])->name('role-save.store');
    Route::get('role-edit/{id}', [RoleController::class, 'editPermission'])->name('role-edit.editPermission');
    Route::post('role-update/{id}', [RoleController::class ,'update'])->name('role-update.update');
    Route::delete('role-delete/{id}', [RoleController::class, 'delete'])->name('role-delete.delete');

    //settings route
    Route::get('app-settings', [SettingController::class, 'settingIndex'])->name('app-settings.settingIndex');
    Route::put('app-settings/{setting}', [SettingController::class, 'appSettingUpdate'])->name('appSettingUpdate');
    Route::get('email-settings', [SettingController::class, 'emailSetting'])->name('emailSetting');
    Route::put('email-settings/{setting}', [SettingController::class, 'emailSettingUpdate'])->name('emailSettingUpdate');

    Route::resource('testimonial', TestimonialController::class);
    Route::put('testimonial', [TestimonialController::class, 'testimonialUpdate'])->name('setting.testimonialUpdate');

    Route::resource('service', ServiceController::class);
    Route::put('service-up', [ServiceController::class, 'servicesUpdate'])->name('setting.servicesUpdate');
    //how-we-work
    Route::resource('how-we-work', HowWorkController::class);
    Route::put('how-we-work', [HowWorkController::class, 'howWorkUpdate'])->name('setting.howWorkUpdate');

    //logo icon settings
    Route::get('logo-icon', [GeneralSettingController::class, 'logoIcon'])->name('logoIcon.Setting');
    Route::put('logo-icon', [GeneralSettingController::class, 'logoIconUpdate'])->name('logoIconUpdate.Setting');
    Route::get('social-link', [GeneralSettingController::class, 'social'])->name('social.Setting');
    Route::post('social-link', [GeneralSettingController::class, 'socialAdd'])->name('socialAdd.Setting');
    Route::put('social-link/{social}', [GeneralSettingController::class, 'socialUpdate'])->name('socialUpdate.Setting');
    Route::delete('social-link-delete/{id}', [GeneralSettingController::class, 'socialDestroy'])->name('socialDestroy.Setting');

    Route::get('header-text', [GeneralSettingController::class, 'headerTextSetting'])->name('headerTextSetting');
    Route::put('header-text/{setting}', [GeneralSettingController::class, 'headerTextSettingUpdate'])->name('headerTextUpSetting');
    Route::get('footer-setting', [GeneralSettingController::class, 'footer'])->name('footer.Setting');
    Route::put('footer-setting', [GeneralSettingController::class, 'updateFooter'])->name('updateFooter.Setting');

    Route::get('aboutus', [GeneralSettingController::class, 'aboutus'])->name('aboutus.Setting');
    Route::put('aboutus', [GeneralSettingController::class, 'updateAboutUs'])->name('updateAboutUs.Setting');
    Route::get('counter', [GeneralSettingController::class, 'counter'])->name('counter.Setting');
    Route::put('counter/{setting}', [GeneralSettingController::class, 'updateCounter'])->name('updateCounter.Setting');
    //inbox
    Route::get('inbox', [InboxContactController::class, 'contactMessage'])->name('contactMessage');
    Route::get('read-message/{contact}', [InboxContactController::class, 'readMessage'])->name('readMessage');
    Route::delete('delete-message/{contact}', [InboxContactController::class, 'destroy'])->name('message.destroy');

    //user create
    Route::get('users', [UserController::class, 'userList'])->name('users.userList');
    Route::get('users/create', [UserController::class, 'createUser'])->name('createUser');
    Route::post('user-store', [UserController::class, 'saveUser'])->name('saveUser');
    Route::get('user/{id}/edit', [UserController::class, 'userEdit'])->name('userEdit');
    Route::put('user-update/{id}', [UserController::class, 'userUpdate'])->name('userUpdate');


    //custom fields
    Route::get('custom-fields', [CustomFieldController::class, 'index'])->name('CustomFields');
    Route::get('get-custom-field-data', [CustomFieldController::class, 'getCustomFieldData'])->name('getCustomFieldData');
    Route::post('custom-field-store', [CustomFieldController::class, 'store'])->name('CustomFieldStore');
    Route::get('custom-field/{id}/edit', [CustomFieldController::class, 'edit'])->name('CustomFieldEdit');
    Route::post('custom-field-update/{id}', [CustomFieldController::class, 'update'])->name('CustomFieldUpdate');
    Route::delete('custom-field-delete/{id}', [CustomFieldController::class, 'destroy'])->name('CustomFieldDelete');
    Route::get('custom-fields/{id}/options', [CustomFieldController::class, 'fieldOptions'])->name('CustomFieldOptions');
    Route::get('get-options-field-data/{id}', [CustomFieldController::class, 'fieldOptionsData'])->name('fieldOptionsData');
    Route::get('option-field/{id}/edit', [CustomFieldController::class, 'optionEdit'])->name('optionEdit');
    Route::post('custom-field-store/{id}/option', [CustomFieldController::class, 'storeOption'])->name('CustomFieldOptionStore');
    Route::post('custom-field-update-option/{id}', [CustomFieldController::class, 'updateOption'])->name('updateOption');

    //notification read
    Route::get('notification-read/{id}', [NotificationController::class, 'notificationRead'])->name('notificationRead');

    // translation
    Route::get('translations', [LanguageController::class, 'index'])->name('languages.index');
    Route::get('translations/language/create', [LanguageController::class, 'create'])->name('languages.create');
    Route::post('translations/language/store', [LanguageController::class, 'store'])->name('languages.store');

    Route::get('translations/{language}', [LanguageTranslationController::class, 'index'])->name('language.translations.index');
    Route::get('translations/create/{language}', [LanguageTranslationController::class, 'create'])->name('language.translations.create');
    Route::post('translations/store/{language}', [LanguageTranslationController::class, 'store'])->name('language.translations.store');
    Route::get('translations/update/{id}/{value}', [LanguageTranslationController::class, 'update'])->name('language.translations.update');
    // languagePublish
    Route::post('translations/language-publish', [LanguageTranslationController::class, 'languagePublish'])->name('language.translations.publish');
    Route::post('translations/language-import', [LanguageTranslationController::class, 'languageImport'])->name('language.translations.import');
});

Route::group(['prefix' => 'laravel-filemanager', 'middleware' => ['web', 'auth']], function () {
    \UniSharp\LaravelFilemanager\Lfm::routes();
});
