<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\DeviceController;
use App\Http\Controllers\Api\ForgotPasswordController;
use App\Http\Controllers\Api\PatientsController;
use App\Http\Controllers\Api\StaffsController;
use App\Http\Controllers\Api\TrainingCoursesController;
use App\Http\Controllers\Api\ChatsController;
use App\Http\Controllers\Api\TasksController;
use App\Http\Controllers\Api\NotificationController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

/*Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();get-user-app-info
});*/


Route::post('login', [AuthController::class, 'login']);
Route::post('forgot-password', [ForgotPasswordController::class, 'forgotPassword']);

Route::post('add-update-device-info', [DeviceController::class, 'appUpdateDeviceInfo']);
Route::post('update-forcefully-logout-status', [DeviceController::class, 'updateForceFullyLogoutStatus']);
Route::post('get-user-app-info', [DeviceController::class, 'getUserAppInfo']);
Route::group(['middleware' => ['auth:sanctum']], function() {
	//Dashboard Route
	Route::post('dashboard-data', [DashboardController::class, 'index']);
	Route::post('update-notification-status', [DashboardController::class, 'updateNotificationStatus']);
	
	//Patient Route
	Route::post('update-password', [AuthController::class, 'changePassword']);
	Route::post('add-update-patients', [PatientsController::class, 'addUpdatePatients']);
	Route::post('patients-list', [PatientsController::class, 'getPatientsList']);
	Route::post('task-patients-list', [PatientsController::class, 'getTaskPatientsList']);
	Route::post('patients-by-id', [PatientsController::class, 'getPatientByID']);
	Route::post('delete-patients', [PatientsController::class, 'deletePatients']);
	Route::post('discharge-patient', [PatientsController::class, 'dischargePatient']);
	// Route::post('send-incident-report-notifications', [PatientsController::class, 'sendIncidentReportNotification']);
	//Patient Medicine Route
	Route::post('patients-get-medicine-list', [PatientsController::class, 'getPatientsMedicineList']);
	Route::post('get-add-update-medicine-form-data', [PatientsController::class, 'getMedicineFormDate']);
	Route::post('patients-add-update-medicine', [PatientsController::class, 'addUpdatePatientsMedicine']);
	Route::post('patients-delete-medicine', [PatientsController::class, 'deletePatientsMedicine']);
	Route::post('patients-get-appointments', [PatientsController::class, 'getPatientsAppointments']);
	Route::post('patients-appointment-action', [PatientsController::class, 'getPatientsAction']);
	//Patient Document Route
	Route::post('patients-get-document-list', [PatientsController::class, 'getPatientsDocumentList']);
	Route::post('patients-add-update-document', [PatientsController::class, 'addUpdatePatientsDocument']);
	Route::post('patients-delete-document', [PatientsController::class, 'deletePatientsDocument']);

	//Patient Expense Route
	Route::post('patients-get-expense-list', [PatientsController::class, 'getPatientsExpenseList']);
	Route::post('patients-add-update-expense', [PatientsController::class, 'addUpdatePatientsExpense']);
	Route::post('patients-delete-expense', [PatientsController::class, 'deletePatientsExpense']);

	//Patient Incident Route
	Route::post('incidents-list', [PatientsController::class, 'getPatientsIncidentList']);
	Route::post('add-update-incident', [PatientsController::class, 'addUpdatePatientsIncident']);
	Route::post('delete-incident', [PatientsController::class, 'deleteIncident']);
	//Patient Log Route
	Route::post('patients-log-time-info', [PatientsController::class, 'getShiftLogTimeInfo']);
	Route::post('patients-log-list', [PatientsController::class, 'getPatientsLogList']);
	Route::post('patients-med-log-list', [PatientsController::class, 'getPatientsMedLogList']);
	Route::post('patients-add-update-med-log', [PatientsController::class, 'addUpdateMedLog']);
	Route::post('patients-med-list-by-type', [PatientsController::class, 'getPatientsMedListByType']);
	Route::post('patients-multi-med-list-by-type', [PatientsController::class, 'getPatientsMultiMedListByType']);
	Route::post('patients-get-med-detail-by-id', [PatientsController::class, 'getPatientsMedDetailById']);
	Route::post('patients-log-form', [PatientsController::class, 'getPatientsLogForm']);
	Route::post('patients-ad-hoc-form', [PatientsController::class, 'getPatientsAdHocForm']);
	Route::post('patients-add-update-log', [PatientsController::class, 'addUpdatePatientsLog']);
	Route::post('patients-single-log', [PatientsController::class, 'getPatientLogByID']);
	Route::post('patients-single-log-by-shift', [PatientsController::class, 'getPatientLogByIDShift']);
	Route::post('patients-delete-log', [PatientsController::class, 'deletePatientlog']);
	Route::post('patients-download-log-report', [PatientsController::class, 'downloadLogReport']); 
	Route::post('discontinue-patient-medicine', [PatientsController::class, 'discontinuePatientsMedicine']); 
	//Staff Route
	Route::post('care-home-staff-list', [StaffsController::class, 'getStaffList']);
	Route::post('task-staff-list', [StaffsController::class, 'getTaskStaffList']);
	Route::post('care-home-create-update-staff', [StaffsController::class, 'addUpdateStaff']);
	Route::post('care-home-update-staff-shift', [StaffsController::class, 'addUpdateStaffShift']);
	Route::post('get-staff-detail', [StaffsController::class, 'getStaffDetail']);

	//Training Course Route
	Route::post('training-course-add-update', [TrainingCoursesController::class, 'addUpdateTrainingCourse']);
	Route::post('training-course-get-list', [TrainingCoursesController::class, 'getTrainingCoursesList']);
	Route::post('training-course-delete', [TrainingCoursesController::class, 'deleteTrainingCourse']);
	Route::post('training-assign-to-staff', [TrainingCoursesController::class, 'assignCourseToStaff']);
	Route::post('training-upload-certificate', [TrainingCoursesController::class, 'uploadTrainingCertificate']);
	Route::post('training-assign-staff-list', [TrainingCoursesController::class, 'getStaffAssignedCoursesList']);
	Route::post('training-assign-staff-delete', [TrainingCoursesController::class, 'deleteStaffCourse']);
	//Chat routes
	Route::post('get-chat', [ChatsController::class, 'getChats'])->name('get-chat');
	Route::post('chat-send-message', [ChatsController::class, 'saveMessage'])->name('chat-send-message');
	Route::post('send-feedback-admin', [ChatsController::class, 'sendAdminFeedback'])->name('send-feedback-admin');
	Route::post('chat-get-message', [ChatsController::class, 'getMessage'])->name('chat-get-message');
	//Tasks routes
	Route::post('assigned-task-list', [TasksController::class, 'getAssignedTaskList']);
	Route::post('task-status-update', [TasksController::class, 'updateTaskStatus']);
	Route::post('add-update-task', [TasksController::class, 'addUpdateTasks']);
	Route::post('delete-task', [TasksController::class, 'deleteTask']);
	Route::post('send-notification', [NotificationController::class, 'sendNotification']);

	// Logout

	Route::post('logout', [AuthController::class, 'logout']);

});

// Route::post('forgot-password', 'ForgotPasswordController@forgotPassword');


