<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\Auth\NewVerifyEmailController;
use App\Http\Controllers\SubscriptionPlanController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\CareHomesController;
use App\Http\Controllers\ActivitiesController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RevenuesController;
use App\Http\Controllers\ExpensesController;
use App\Http\Controllers\FaqsController;
use App\Http\Controllers\CareHomeSubscriptionController;
use App\Http\Controllers\StripeWebhookController;
use App\Http\Controllers\PagesController;
use App\Http\Controllers\PatientsController;
use App\Http\Controllers\NotificationsController;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\TrainingCoursesController;
use App\Http\Controllers\ChatsController;
use App\Http\Controllers\CouponsController;
use App\Http\Controllers\DiscountsController;
use App\Http\Controllers\TasksController;
use App\Http\Controllers\EmailLogsController;
use App\Http\Controllers\StaffActivityHistoryController;
use App\Http\Controllers\IncidentController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/faq', [HomeController::class, 'faqPage'])->name('faq');
Route::get('/contact-us', [HomeController::class, 'contactUs'])->name('contact-us');
Route::post('/save-contact-us', [HomeController::class, 'saveContactUs'])->name('save-contact-us');
Route::get('/term-conditions', [HomeController::class, 'termsPage'])->name('term-conditions');
Route::get('/privacy-policy', [HomeController::class, 'privacyPolicyPage'])->name('privacy-policy');
Route::get('/about-us', [HomeController::class, 'aboutUsPage'])->name('about-us');
Route::get('/otp-verify', [AuthenticatedSessionController::class, 'otpVerify'])->name('otp.verify');
Route::get('/staff-verified', [AuthenticatedSessionController::class, 'staffVerified'])->name('staff-verified');
Route::post('/otp-verified', [AuthenticatedSessionController::class, 'otpVerified'])->name('otp.verified');
Route::get('/resend-otp', [AuthenticatedSessionController::class, 'resendOtp'])->name('otp.resend');

Route::get('/verify-email-test', [NewVerifyEmailController::class, 'verify']);
//stripe webhook route
//Route::post('webhooks/stripe', [StripeWebhookController::class, 'handleWebhook']);
Route::post('stripe/webhook', [StripeWebhookController::class, 'handleWebhook']);

Route::middleware(['auth', 'verified','check.status'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    //Route::patch('/password/update', [ProfileController::class, 'updatePassword'])->name('password.update');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    /** Subscription Management*/
    Route::get('/subscription-plans', [SubscriptionPlanController::class, 'index'])->name('subscription_plans.index');
    Route::get('/subscription-plans/get-data', [SubscriptionPlanController::class, 'getData'])->name('subscription_plans.getData');
    Route::get('/subscription-plans/create', [SubscriptionPlanController::class, 'create'])->name('subscription_plans.create');
	Route::post('/subscription-plans/store', [SubscriptionPlanController::class, 'store'])->name('subscription_plans.store');
    Route::get('/subscription-plans/edit/{id?}', [SubscriptionPlanController::class, 'edit'])->name('subscription_plans.getEdit');
    Route::post('/subscription-plans/update', [SubscriptionPlanController::class, 'update'])->name('subscription_plans.updateSubscription');
    Route::post('/subscription-plans/update-permission', [SubscriptionPlanController::class, 'updateSubscriptionPermission'])->name('subscription_plans.update-subscription-permission');
    Route::get('/subscription-plans/subscription-price/{id?}', [SubscriptionPlanController::class, 'subscriptionPrice'])->name('subscription_plans.subscriptionPrice');
    Route::post('/subscription-plans/update-subscription-price', [SubscriptionPlanController::class, 'updateSubscriptionPrice'])->name('subscription_plans.update-subscription-price');
    Route::post('/subscription-plans/add', [SubscriptionPlanController::class, 'store'])->name('subscription_plans.addSubscription');
    // Route::delete('/subscription-plans/delete/{id}', [SubscriptionPlanController::class, 'delete'])->name('subscription_plans.delete');
	/** Users Management*/
    Route::resource('users', UsersController::class);
    Route::post('/change-user-status', [UsersController::class, 'changeUserStatus'])->name('change-user-status');
    Route::post('/care-home-users', [UsersController::class, 'careHomeUsers'])->name('care-home-users');
    Route::get('/users-get-data', [UsersController::class, 'getData'])->name('users-get-data');
    Route::get('/care-home-get-data', [UsersController::class, 'careHomeGetData'])->name('care-home-get-data');
    Route::post('/check-is-admin-add-staff', [UsersController::class, 'checkIsAdminAbleToAddStaff'])->name('check-is-admin-add-staff');

    Route::post('/check-is-admin-add-staff-restore', [UsersController::class, 'checkIsAdminAbleToAddStaffRestore'])->name('check-is-admin-add-staff-restore');
    Route::post('/check-is-admin-add-patient', [UsersController::class, 'checkIsAdminAbleToAddPatient'])->name('check-is-admin-add-patient');

    Route::get('/care-home-admin', [UsersController::class, 'careHomeAdmin'])->name('care-home-admin');

    Route::post('/get-staff-count', [UsersController::class, 'getStaffCount'])->name('get-staff-count');
    /** Assigned Tasks to User*/
    Route::get('view-user-assigned-task/{id}', [UsersController::class, 'viewUserAssignedTask'])->name('view-user-assigned-task');
	/** Care Home Management*/
    
    Route::resource('homes', CareHomesController::class);
   Route::post('/check-home-email-exsist', [CareHomesController::class, 'ishomeEmailExsist'])->name('check-home-email-exsist');
    Route::get('/homes-get-data', [CareHomesController::class, 'getData'])->name('homes-get-data');
    Route::get('/homes-search-users', [CareHomesController::class, 'searchUser'])->name('homes-search-users');
    Route::post('/pause-subscription', [CareHomesController::class, 'pauseSubscription'])->name('pause-subscription');
    Route::post('/resume-subscription', [CareHomesController::class, 'resumeSubscription'])->name('resume-subscription');
    Route::post('/cancel-subscription', [CareHomesController::class, 'cancelSubscription'])->name('cancel-subscription');
	Route::get('/subscription-manage/{id}', [CareHomesController::class, 'subscriptionManage'])->name('subscription-manage');
	Route::post('/handle-subscription-manage', [CareHomesController::class, 'handleSubscriptionManage'])->name('handle-subscription-manage');
    Route::get('/add-update-home-document/{home_id}/{id}', [CareHomesController::class, 'addUpdateHomeDocument'])->name('add-update-home-document');
    Route::post('save-home-document/{home_id}/{id}', [CareHomesController::class, 'savehomeDocument'])->name('save-home-document');
    Route::delete('delete-home-document/{id}', [CareHomesController::class, 'deleteHomeDocument'])->name('delete-home-document');
    Route::post('/get-care-home-staff', [CareHomesController::class, 'getCareHomeStaff'])->name('get-care-home-staff');

    
	//Care Home Activity Time
	Route::get('/get-activity-time-form/{home_id}', [CareHomesController::class, 'getActivityTimeForm'])->name('get-activity-time-form');
    Route::post('/save-activity-time-form', [CareHomesController::class, 'saveActivityTimeForm'])->name('save-activity-time-form');
	/** Activities Management*/
    Route::resource('activities', ActivitiesController::class);
    Route::get('/activities-get-data', [ActivitiesController::class, 'getData'])->name('activities-get-data');
    Route::get('/get-care-home-patients', [ActivitiesController::class, 'getCareHomePatients'])->name('get-care-home-patients');
    //Route::get('/users/view-detail/{id}', [UsersController::class, 'viewUserDetails'])->name('users.view-detail');
    //Route::get('/users/add-update-user/{id}', [UsersController::class, 'createUpdateUser'])->name('users.add-update-user');
	/** Patient Management*/
    Route::resource('patients', PatientsController::class);
    Route::post('/check-email-exsist', [PatientsController::class, 'isEmailExsist'])->name('patients.check-email-exsist');
    Route::get('/patients-get-data', [PatientsController::class, 'getData'])->name('patients-get-data');
    Route::post('/patient-activity-by-shift-date', [PatientsController::class, 'patientActivityByShiftDate'])->name('patient-activity-by-shift-date');
    Route::get('/add-patient-medicine-report/{patient_id}/{shift_id}', [PatientsController::class, 'addPatientMedicineReport'])->name('add-patient-medicine-report');
    Route::get('/update-patient-medicine-report/{patient_id}/{shift_id}', [PatientsController::class, 'updatePatientMedicineReport'])->name('update-patient-medicine-report');
    Route::get('patients-show-activity/{id}', [PatientsController::class, 'viewPatientActivity'])->name('patients-show-activity');
    Route::get('patients-show-activity-detail/{patient_id}/{id}', [PatientsController::class, 'viewPatientActivityDetail'])->name('patients-show-activity-detail');
    Route::get('patients-download-activity-detail/{id}', [PatientsController::class, 'downloadPatientActivityLog'])->name('patients-download-activity-detail');
    Route::get('add-update-patients-medicine/{patient_id}/{id}', [PatientsController::class, 'addUpdatePatientsMedicine'])->name('add-update-patients-medicine');
    Route::get('view-patient-medication-report/{patient_id}/{id}', [PatientsController::class, 'viewPatientsMedicineByMonth'])->name('view-patient-medication-report');
    Route::post('download-patient-medication-report/{patient_id}', [PatientsController::class, 'downloadPatientMedicineReport'])->name('download-patient-medication-report');
    Route::post('save-patients-medicine/{patient_id}/{id}', [PatientsController::class, 'savePatientsMedicine'])->name('save-patients-medicine');
    Route::delete('delete-patients-medicine/{id}', [PatientsController::class, 'deletePatientsMedicine'])->name('delete-patients-medicine');
    Route::post('discontinue-patient-medicine/{patient_id}', [PatientsController::class, 'discontinuePatientsMedicine'])->name('discontinue-patient-medicine');
	Route::get('add-update-patients-document/{patient_id}/{id}', [PatientsController::class, 'addUpdatePatientsDocument'])->name('add-update-patients-document');
    Route::get('add-update-patients-doctor/{patient_id}/{id}', [PatientsController::class, 'addUpdatePatientsDoctor'])->name('add-update-patients-doctor');
    Route::post('save-patients-doctor/{patient_id}/{id}', [PatientsController::class, 'savePatientsDoctor'])->name('save-patients-doctor');
    Route::get('add-update-patients-schedule/{patient_id}/{id}', [PatientsController::class, 'addUpdatePatientsSchedule'])->name('add-update-patients-schedule');
    Route::post('save-patients-document/{patient_id}/{id}', [PatientsController::class, 'savePatientsDocument'])->name('save-patients-document');
    Route::get('/get-care-home-staffs', [PatientsController::class, 'getCareHomeStaffs'])->name('get-care-home-staffs');
    Route::get('get-daily-activity-form/{patient_id}/{shift_id}', [PatientsController::class, 'getDailyActivityFormBySlug'])->name('get-daily-activity-form');
    Route::post('add-daily-activity-detail/{patient_id}/{id?}', [PatientsController::class, 'addDailyActivityForm'])->name('add-daily-activity-detail');
    Route::post('add-daily-medicine-detail/{patient_id}/{id?}', [PatientsController::class, 'addDailyMedicineForm'])->name('add-daily-medicine-detail');
    Route::post('save-staff-note/{patient_id}', [PatientsController::class, 'saveStaffNote'])->name('save-staff-note');
    Route::post('add-new-doctor', [PatientsController::class, 'addNewDoctor'])->name('add-new-doctor');

    Route::post('save-patients-schedule/{patient_id}/{id}', [PatientsController::class, 'savePatientsSchedule'])->name('save-patients-schedule');
    Route::get('view-patients-schedule/{patient_id}/{id}', [PatientsController::class, 'viewPatientsSchedule'])->name('view-patients-schedule');

    Route::delete('delete-patients-document/{id}', [PatientsController::class, 'deletePatientsDocument'])->name('delete-patients-document');

    Route::delete('delete-patients-schedule/{id}', [PatientsController::class, 'deletePatientsSchedule'])->name('delete-patients-schedule');
    Route::delete('delete-patient/{id}', [PatientsController::class, 'deletePatient'])->name('delete-patient');

    Route::get('delete-patient-request/{id}', [PatientsController::class, 'deletePatientRequest'])->name('delete-patient-request');

	Route::get('/update-patients-discharge-status', [PatientsController::class, 'updatePatientDischargeStatus'])->name('update-patients-discharge-status');
    /** Assign Activity to Patient*/
    Route::get('view-patient-assigned-activity/{patient_id}/{id}', [PatientsController::class, 'viewPatientAssignedActivity'])->name('view-patient-assigned-activity');
    Route::get('assign-activity-to-patient/{patient_id}/{id}', [PatientsController::class, 'addUpdatePatientAssignedActivity'])->name('assign-activity-to-patient');
    Route::post('save-patient-assign-activity/{patient_id}/{id}', [PatientsController::class, 'savePatientAssignedActivity'])->name('save-patient-assign-activity');
    Route::delete('delete-patient-assigned-activity/{id}', [PatientsController::class, 'deletePatientsAssignedActivity'])->name('delete-patient-assigned-activity');  
    /** Assigned Tasks to Patient*/
    Route::get('view-patient-assigned-task/{id}', [PatientsController::class, 'viewPatientAssignedTask'])->name('view-patient-assigned-task');
    /** Users Subscription Management*/
    Route::get('care-homes-subscription/{id?}', [CareHomeSubscriptionController::class,'index'])->name("care-homes-subscription.index");
    Route::resource('care-homes-subscription', CareHomeSubscriptionController::class)->except(['index']);
    Route::get('care-homes-subscription/{home_id}/{plan_id}', [CareHomeSubscriptionController::class, 'show'])->name("subscription-plan-show");
    Route::post('buy-subscription', [CareHomeSubscriptionController::class, 'buySubscription'])->name("subscription.create");
    Route::post('staff-add-on-subscription/{home_id}/{plan_id}', [CareHomeSubscriptionController::class, 'staffAddOnSubscription'])->name("subscription.staffAddOnSubscription");
	 /** Revenue Management*/
    Route::resource('revenues', RevenuesController::class);
    Route::get('/subscription-revenues-get-data', [RevenuesController::class, 'getSubscriptionData'])->name('subscription-revenues-get-data');
    Route::get('/revenue/{id}', [RevenuesController::class, 'getRevenueData'])->name('revenue');
    Route::get('/last-week-revenue', [RevenuesController::class, 'getLastWeekRevenueData'])->name('last-week-revenue');
    Route::get('/patient-revenues-get-data', [RevenuesController::class, 'getPatientData'])->name('patient-revenues-get-data');
	/** Expense Management*/
	Route::resource('expenses', ExpensesController::class);
	Route::get('patient-expenses-get-data', [ExpensesController::class, 'getExpensesData'])->name('patient-expenses-get-data');
    Route::post('/care-home-patients', [ExpensesController::class, 'careHomePatients'])->name('care-home-patients');
	/** Faq Management*/
    Route::resource('faqs', FaqsController::class);
    Route::get('/faqs-get-data', [FaqsController::class, 'getData'])->name('faqs-get-data');
	/** Notifications Management*/
    Route::resource('notifications', NotificationsController::class);
    Route::get('/notifications-get-data', [NotificationsController::class, 'getData'])->name('notifications-get-data');

    Route::get('/push-notification', [NotificationsController::class, 'pushNotification'])->name('push-notification');
    Route::post('/send-push-notification', [NotificationsController::class, 'sendPushNotification'])->name('send-push-notification');
    /* MARKETING WEBSITE => Resources */
	Route::resource('pages', PagesController::class);

    /* Training Courses */
    Route::resource('assign-training-to-staff', TrainingCoursesController::class);
    Route::get('/staff-training-course-get-data', [TrainingCoursesController::class, 'getData'])->name('staff-training-course-get-data');
    Route::post('/upload-training-certificate', [TrainingCoursesController::class, 'uploadTrainingCertificate'])->name('upload-training-certificate');
    Route::get('/training-course-list', [TrainingCoursesController::class, 'viewTraningCourseList'])->name('training-course-list');
    Route::get('/get-training-course-list', [TrainingCoursesController::class, 'getTrainingCoursesList'])->name('get-training-course-list');
    Route::get('add-update-training-course/{id?}', [TrainingCoursesController::class, 'addUpdateTrainingCourse'])->name('add-update-training-course');
    Route::post('save-training-course/{id?}', [TrainingCoursesController::class, 'saveTrainingCourse'])->name('save-training-course');
    Route::post('complete-training-by-admin/{id?}', [TrainingCoursesController::class, 'completeTrainingByAdmin'])->name('complete-training-by-admin');
    Route::delete('delete-training-course/{id}', [TrainingCoursesController::class, 'deleteTrainingCourse'])->name('delete-training-course');
	/** Chat Management*/
    Route::get('chat', [ChatsController::class, 'index'])->name('chat.index');
    Route::get('/get-chat-message', [ChatsController::class, 'getMessage'])->name('get-chat-message');
    Route::post('/save-chat-message', [ChatsController::class, 'saveMessage'])->name('save-chat-message');
    Route::post('/super-admin-allow', [ChatsController::class, 'superAdminAllow'])->name('super-admin-allow');
	/** Coupon Code Management*/
    Route::resource('coupons', CouponsController::class);
    Route::get('coupons-get-data', [CouponsController::class, 'getData'])->name('coupons-get-data');
    Route::get('verify-coupons-code', [CouponsController::class, 'verifyCoupon'])->name('verify-coupons-code');
	/** Discount Management*/
    Route::resource('discounts', DiscountsController::class);
    Route::get('discounts-get-data', [DiscountsController::class, 'getData'])->name('discounts-get-data');
    /** Task Management*/
    Route::resource('tasks', TasksController::class);
    Route::get('/tasks-list', [TasksController::class, 'viewTasksList'])->name('tasks-list'); 
    Route::get('/get-tasks-list', [TasksController::class, 'getTasksList'])->name('get-tasks-list');
    Route::get('add-update-tasks/{id?}', [TasksController::class, 'addUpdateTasks'])->name('add-update-tasks');
    Route::post('save-tasks/{id?}', [TasksController::class, 'saveTasks'])->name('save-tasks');
    Route::delete('delete-task/{id}', [TasksController::class, 'deleteTask'])->name('delete-task');
	
    /** Task Management*/
    Route::resource('staff-activity-history', StaffActivityHistoryController::class);
    Route::get('/staff-activity-history-list', [StaffActivityHistoryController::class, 'viewStaffActivityHistoryList'])->name('staff-activity-history-list'); 
    Route::get('/get-staff-activity-history-list', [StaffActivityHistoryController::class, 'getStaffActivityHistoryList'])->name('get-staff-activity-history-list');

    Route::get('/get-staff-traning-list/{id}', [StaffActivityHistoryController::class, 'getStaffTrainingList'])->name('get-staff-traning-list');

    Route::get('/incident-reports-list',[IncidentController::class,'incidentReportsList'])->name('incident-reports-list');
    Route::get('/get-incident-list',[IncidentController::class,'getIncidentReportList'])->name('get-incident-list');
    Route::get('patients-show-incident-detail/{id}', [IncidentController::class, 'viewPatientIncidentDetail'])->name('patients-show-incident-detail');
    Route::get('patients-download-incident-detail/{id}', [IncidentController::class, 'downloadPatientIncidents'])->name('patients-download-incident-detail');
    Route::get('download-single-incident-detail/{id}', [IncidentController::class, 'downloadSingleIncident'])->name('download-single-incident-detail');


    /** EmailLogs Management*/
    Route::resource('email-logs', EmailLogsController::class);
    Route::get('/email-logs-get-data', [EmailLogsController::class, 'getData'])->name('email-logs-get-data');

    Route::get('/chat-message-count', [ChatsController::class, 'getChatMessageCount'])->name('chat-message-count');
    
});

require __DIR__.'/auth.php';
