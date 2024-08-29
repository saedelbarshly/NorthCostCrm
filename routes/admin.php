<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\admin\RolesController;
use App\Http\Controllers\admin\CitiesController;
use App\Http\Controllers\admin\hr\JobsController;
use App\Http\Controllers\admin\ReportsController;
use App\Http\Controllers\admin\BranchesController;
use App\Http\Controllers\admin\PaymentsController;
use App\Http\Controllers\admin\ServicesController;
use App\Http\Controllers\admin\SettingsController;
use App\Http\Controllers\admin\ContractsController;
use App\Http\Controllers\admin\StatisticController;
use App\Http\Controllers\admin\AdminPanelController;
use App\Http\Controllers\admin\AdminUsersController;
use App\Http\Controllers\admin\hr\SalariesController;
use App\Http\Controllers\admin\GovernoratesController;
use App\Http\Controllers\admin\hr\AttendanceController;
use App\Http\Controllers\admin\hr\DeductionsController;
use App\Http\Controllers\admin\location\CityController;
use App\Http\Controllers\admin\accounts\SafesController;
use App\Http\Controllers\admin\hr\ManagementsController;
use App\Http\Controllers\admin\location\RegionController;
use App\Http\Controllers\admin\units\UniteTypeController;
use App\Http\Controllers\admin\accounts\ExpensesController;
use App\Http\Controllers\admin\accounts\RevenuesController;
use App\Http\Controllers\admin\brokerOwnor\OwnerController;
use App\Http\Controllers\admin\brokerOwnor\BrokerController;
use App\Http\Controllers\admin\units\UnitsController;
use App\Http\Controllers\admin\accounts\ExpensesTypesController;
use App\Http\Controllers\admin\projectsUnits\ProjectsController;
use App\Http\Controllers\admin\projectsUnits\TemplatesController;
use App\Http\Controllers\admin\accounts\MoneyTransfeersController;
use App\Http\Controllers\admin\ClientsFollowUps\ClientsController;
use App\Http\Controllers\admin\ClientsFollowUps\FollowUpsController;
use App\Http\Controllers\admin\ClientsFollowUps\ClientSourcesController;

Route::group(['prefix'=>'AdminPanel','middleware'=>['isAdmin','auth']], function(){

    // Route::get('/',[AdminPanelController::class,'index'])->name('admin.index');
    Route::get('/',[AdminPanelController::class,'graf'])->name('admin.index');

    // Route::get('/today',[StatisticController::class,'today'])->name('admin.today');

    Route::get('/read-all-notifications',[AdminPanelController::class,'readAllNotifications'])->name('admin.notifications.readAll');
    Route::get('/notification/{id}/details',[AdminPanelController::class,'notificationDetails'])->name('admin.notification.details');

    Route::get('/my-salary',[AdminPanelController::class,'mySalary'])->name('admin.mySalary');

    Route::get('/my-profile',[AdminPanelController::class,'EditProfile'])->name('admin.myProfile');
    Route::post('/my-profile',[AdminPanelController::class,'UpdateProfile'])->name('admin.myProfile.update');
    Route::get('/my-password',[AdminPanelController::class,'EditPassword'])->name('admin.myPassword');
    Route::post('/my-password',[AdminPanelController::class,'UpdatePassword'])->name('admin.myPassword.update');
    Route::get('/notifications-settings',[AdminPanelController::class,'EditNotificationsSettings'])->name('admin.notificationsSettings');
    Route::post('/notifications-settings',[AdminPanelController::class,'UpdateNotificationsSettings'])->name('admin.notificationsSettings.update');

    Route::group(['prefix'=>'admins'], function(){
        Route::get('/',[AdminUsersController::class,'index'])->name('admin.adminUsers');
        Route::get('/create',[AdminUsersController::class,'create'])->name('admin.adminUsers.create');
        Route::post('/create',[AdminUsersController::class,'store'])->name('admin.adminUsers.store');
        Route::get('/{id}/block/{action}',[AdminUsersController::class,'blockAction'])->name('admin.adminUsers.block');
        Route::get('/{id}/edit',[AdminUsersController::class,'edit'])->name('admin.adminUsers.edit');
        Route::post('/{id}/edit',[AdminUsersController::class,'update'])->name('admin.adminUsers.update');
        Route::get('/{id}/hrProfile',[AdminUsersController::class,'hrProfile'])->name('admin.adminUsers.hrProfile');
        Route::post('/{id}/hrProfile',[AdminUsersController::class,'updateHRProfile'])->name('admin.adminUsers.updateHRProfile');
        Route::get('/{id}/delete',[AdminUsersController::class,'delete'])->name('admin.adminUsers.delete');
        Route::get('/{id}/DeletePhoto/{photo}/{X}', [AdminUsersController::class,'DeleteuserPhoto'])->name('admin.users.deletePhoto');
    });

    Route::group(['prefix'=>'managements'], function(){
        Route::get('/',[ManagementsController::class,'index'])->name('admin.managements');
        Route::post('/create',[ManagementsController::class,'store'])->name('admin.managements.store');
        Route::post('/{id}/edit',[ManagementsController::class,'update'])->name('admin.managements.update');
        Route::get('/{id}/delete',[ManagementsController::class,'delete'])->name('admin.managements.delete');
    });

    Route::group(['prefix'=>'jobs'], function(){
        Route::get('/',[JobsController::class,'index'])->name('admin.jobs');
        Route::post('/create',[JobsController::class,'store'])->name('admin.jobs.store');
        Route::post('/{id}/edit',[JobsController::class,'update'])->name('admin.jobs.update');
        Route::get('/{id}/delete',[JobsController::class,'delete'])->name('admin.jobs.delete');
    });

    Route::group(['prefix'=>'roles'], function(){
        Route::post('/CreatePermission',[RolesController::class,'CreatePermission'])->name('admin.CreatePermission');

        Route::get('/',[RolesController::class,'index'])->name('admin.roles');
        Route::post('/create',[RolesController::class,'store'])->name('admin.roles.store');
        Route::post('/{id}/edit',[RolesController::class,'update'])->name('admin.roles.update');
        Route::get('/{id}/delete',[RolesController::class,'delete'])->name('admin.roles.delete');
    });


    Route::group(['prefix'=>'governorates'], function(){
        Route::get('/',[GovernoratesController::class,'index'])->name('admin.governorates');
        Route::post('/create',[GovernoratesController::class,'store'])->name('admin.governorates.store');
        Route::post('/{governorateId}/edit',[GovernoratesController::class,'update'])->name('admin.governorates.update');
        Route::get('/{governorateId}/delete',[GovernoratesController::class,'delete'])->name('admin.governorates.delete');

        Route::group(['prefix'=>'{governorateId}/cities'], function(){
            Route::get('/',[CitiesController::class,'index'])->name('admin.cities');
            Route::post('/create',[CitiesController::class,'store'])->name('admin.cities.store');
            Route::post('/{cityId}/edit',[CitiesController::class,'update'])->name('admin.cities.update');
            Route::get('/{cityId}/delete',[CitiesController::class,'delete'])->name('admin.cities.delete');
        });
    });

    Route::group(['prefix'=>'settings'], function(){
        Route::get('/',[SettingsController::class,'generalSettings'])->name('admin.settings.general');
        Route::post('/',[SettingsController::class,'updateSettings'])->name('admin.settings.update');
        Route::get('/{key}/deletePhoto',[SettingsController::class,'deleteSettingPhoto'])->name('admin.settings.deletePhoto');
    });

    Route::group(['prefix'=>'branches'], function(){
		Route::get('/', [BranchesController::class,'index'])->name('admin.branches.index');
		Route::post('/', [BranchesController::class,'store'])->name('admin.branches.store');
		Route::post('{id}/Edit', [BranchesController::class,'update'])->name('admin.branches.update');
		Route::get('{id}/Delete', [BranchesController::class,'delete'])->name('admin.branches.delete');
    });

    Route::group(['prefix'=>'services'], function(){
		Route::get('/', [ServicesController::class,'index'])->name('admin.services.index');
		Route::post('/', [ServicesController::class,'store'])->name('admin.services.store');
		Route::post('{id}/Edit', [ServicesController::class,'update'])->name('admin.services.update');
		Route::get('{id}/Delete', [ServicesController::class,'delete'])->name('admin.services.delete');
    });

    Route::group(['prefix'=>'SalariesControl'], function(){
        //HR Dep. -> Salaries Managment
        Route::get('/', [SalariesController::class,'index'])->name('admin.salaries');
        Route::get('/{id}/Salaries', [SalariesController::class,'EmployeeSalary'])->name('admin.EmployeeSalary');
        Route::post('/{id}/payOutSalary', [SalariesController::class,'payOutSalary'])->name('admin.payOutSalary');

        Route::post('/{id}/AddPermission',[AttendanceController::class,'AddPermission'])->name('admin.AddPermission');
        Route::get('/{id}/DeletePermission',[AttendanceController::class,'DeletePermission'])->name('admin.DeletePermission');
        Route::post('/{id}/AddVacation',[AttendanceController::class,'AddVacation'])->name('admin.AddVacation');
        Route::get('/Vacations/{id}/delete',[AttendanceController::class,'DeleteVacation'])->name('admin.DeleteVacation');

        Route::get('/AttendanceList',[AttendanceController::class,'index'])->name('admin.attendance');
        Route::post('/NewAttendance',[AttendanceController::class,'SubmitNewAttendance'])->name('admin.attendace.excel');

        //HR Dep. -> Salaries Managment -> Records
        Route::group(['prefix'=>'{UID}/Attendance'], function(){
            Route::get('/{Date}/EditVacation',[AttendanceController::class,'EmployeeEditVacation'])->name('EmployeeEditVacation');
            Route::post('/{Date}/EditVacation',[AttendanceController::class,'EmployeePostEditVacation'])->name('EmployeePostEditVacation');
        });

        //HR Dep. -> Salaries Managment -> Add Deduction
        Route::group(['prefix'=>'deductions'], function(){
            Route::post('/store', [DeductionsController::class,'store'])->name('admin.deductions.store');
            Route::post('/{id}/Edit', [DeductionsController::class,'update'])->name('admin.deductions.update');
            Route::get('/{id}/Delete', [DeductionsController::class,'delete'])->name('admin.deductions.delete');
        });
        //test
        Route::post('/{EID}/PaySalary/{Type}',  [HRDepController::class,'PaySalary'])->name('SalaryPay');
        Route::get('/{EID}/PaySalary/{Type}',  [HRDepController::class,'PaySalaryRequest'])->name('SalaryPayRequest');
    });

	/**
	 * Projects & Units Control
	 */

    Route::group(['prefix'=>'templates'], function(){
		Route::get('/', [TemplatesController::class,'index'])->name('admin.templates');
		Route::post('/', [TemplatesController::class,'store'])->name('admin.templates.store');
		Route::post('/{id}/Edit', [TemplatesController::class,'update'])->name('admin.templates.update');
		Route::get('/{id}/Delete', [TemplatesController::class,'delete'])->name('admin.templates.delete');
	});

    Route::group(['prefix'=>'projects'], function(){
		Route::get('/', [ProjectsController::class,'index'])->name('admin.projects');
		Route::post('/', [ProjectsController::class,'store'])->name('admin.projects.store');
		Route::get('/{id}/view', [ProjectsController::class,'view'])->name('admin.projects.view');
		Route::get('/{id}/edit', [ProjectsController::class,'edit'])->name('admin.projects.edit');
		Route::post('/{id}/edit', [ProjectsController::class,'update'])->name('admin.projects.update');
		Route::get('/{id}/DeletePhoto/{photo}/{X}', [ProjectsController::class,'deletePhoto'])->name('admin.projects.deletePhoto');
		Route::get('/{id}/delete', [ProjectsController::class,'delete'])->name('admin.projects.delete');
    });

    // Route::group(['prefix'=>'units'], function(){
    //     Route::get('/', [UnitsController::class,'index'])->name('admin.units');
    //     Route::post('/', [UnitsController::class,'store'])->name('admin.units.store');
	// 	Route::post('/storeExcelUnit', [UnitsController::class,'storeExcelUnit'])->name('admin.units.storeExcelUnit');
	// 	Route::get('/{id}/view', [UnitsController::class,'view'])->name('admin.units.view');
	// 	Route::get('/{id}/edit', [UnitsController::class,'edit'])->name('admin.units.edit');
    //     Route::post('/{id}/edit', [UnitsController::class,'update'])->name('admin.units.update');
    //     Route::get('/{id}/DeletePhoto/{photo}/{X}', [UnitsController::class,'DeleteUnitPhoto'])->name('admin.units.deletePhoto');
    //     Route::get('/{id}/Delete', [UnitsController::class,'delete'])->name('admin.units.delete');
    // });

    /**
	 * Safes Control
	 */

    Route::group(['prefix'=>'Safes'], function(){
		//Safes Control
		Route::get('/', [SafesController::class,'index'])->name('admin.safes');
		Route::post('/', [SafesController::class,'store'])->name('admin.safes.store');
		Route::post('/{id}/Edit', [SafesController::class,'update'])->name('admin.safes.update');
		Route::get('/{id}/Delete', [SafesController::class,'delete'])->name('admin.safes.delete');
		Route::get('/{id}/Stats', [SafesController::class,'Stats'])->name('admin.safes.Stats');
    });

    Route::group(['prefix'=>'moneyTransfeers'], function(){
        Route::get('/',  [MoneyTransfeersController::class,'index'])->name('admin.moneyTransfeers');
        Route::post('/store',  [MoneyTransfeersController::class,'store'])->name('admin.moneyTransfeers.store');
        Route::post('/{id}/Edit',  [MoneyTransfeersController::class,'update'])->name('admin.moneyTransfeers.update');
        Route::get('/{id}/Delete',  [MoneyTransfeersController::class,'delete'])->name('admin.moneyTransfeers.delete');
    });

    Route::group(['prefix'=>'ExpensesTypes'], function(){
        Route::get('/', [ExpensesTypesController::class,'index'])->name('admin.expensesTypes');
        Route::post('/create', [ExpensesTypesController::class,'store'])->name('admin.expensesTypes.store');
        Route::post('/{id}/Edit', [ExpensesTypesController::class,'update'])->name('admin.expensesTypes.update');
        Route::get('/{id}/Delete', [ExpensesTypesController::class,'delete'])->name('admin.expensesTypes.delete');
    });

    Route::group(['prefix'=>'expenses'], function(){
        Route::get('/', [ExpensesController::class,'index'])->name('admin.expenses');
        Route::post('/NewExpense', [ExpensesController::class,'store'])->name('admin.expenses.store');
        Route::post('/{id}/Edit', [ExpensesController::class,'update'])->name('admin.expenses.update');
        Route::get('/{id}/DeletePhoto/{photo}/{X}', [ExpensesController::class,'deletePhoto'])->name('admin.expenses.deletePhoto');
        Route::get('/{id}/Delete', [ExpensesController::class,'delete'])->name('admin.expenses.delete');
    });

    Route::group(['prefix'=>'revenues'], function(){
        Route::get('/', [RevenuesController::class,'index'])->name('admin.revenues');
        Route::post('/NewExpense', [RevenuesController::class,'store'])->name('admin.revenues.store');
        Route::post('/{id}/Edit', [RevenuesController::class,'update'])->name('admin.revenues.update');
        Route::get('/{id}/DeletePhoto/{photo}/{X}', [RevenuesController::class,'deletePhoto'])->name('admin.revenues.deletePhoto');
        Route::get('/{id}/Delete', [RevenuesController::class,'delete'])->name('admin.revenues.delete');
    });


    Route::group(['prefix'=>'contracts'], function(){
		Route::get('/', [ContractsController::class,'index'])->name('admin.contracts.index');
		Route::post('/', [ContractsController::class,'store'])->name('admin.contracts.store');
		Route::get('{id}/Edit', [ContractsController::class,'edit'])->name('admin.contracts.edit');
		Route::post('{id}/Edit', [ContractsController::class,'update'])->name('admin.contracts.update');
        Route::get('/{id}/DeletePhoto/{photo}/{X}', [ContractsController::class,'deleteAttachment'])->name('admin.contracts.deleteAttachment');
		Route::get('{id}/Delete', [ContractsController::class,'delete'])->name('admin.contracts.delete');
        Route::group(['prefix'=>'{id}/payments'], function(){
            Route::get('/', [PaymentsController::class,'index'])->name('admin.payments');
            Route::post('/', [PaymentsController::class,'store'])->name('admin.payments.store');
            Route::post('{pid}/Edit', [PaymentsController::class,'update'])->name('admin.payments.update');
            Route::get('{pid}/Delete', [PaymentsController::class,'delete'])->name('admin.payments.delete');
        });
    });


    	/**
	 *
	 * ClientsControl
	 */

	Route::group(['prefix'=>'clients'], function(){
        Route::get('/getZone/{id}',[ClientsController::class,'getZone']);
		Route::get('/', [ClientsController::class,'index'])->name('admin.clients');
		Route::get('/create', [ClientsController::class,'index'])->name('admin.clients.create');
		Route::get('/search',[ClientsController::class,'search'])->name('admin.clients.search');
		Route::post('/store', [ClientsController::class,'store'])->name('admin.clients.store');
		Route::get('/udpateSupporting/{id}', [ClientsController::class,'udpateSupporting'])->name('admin.clients.udpateSupporting');
		Route::post('/createExcelClient', [ClientsController::class,'storeExcelClient'])->name('admin.clients.storeExcelClient');
        Route::get('/exportExcel', [ClientsController::class,'exportExcel'])->name('admin.clients.exportExcel');
		Route::post('/{id}/Edit', [ClientsController::class,'update'])->name('admin.clients.update');
		Route::get('/{id}/status/{action}', [ClientsController::class,'changeStatus'])->name('admin.clients.changeStatus');
		Route::get('/{id}/Delete', [ClientsController::class,'delete'])->name('admin.clients.delete');

        Route::get('/possibleClients', [ClientsController::class,'possibleClients'])->name('admin.possibleClients');
        Route::get('/receptionClients', [ClientsController::class,'receptionClients'])->name('admin.receptionClients');
		Route::get('/salesCoordinator', [ClientsController::class,'salesCoordinator'])->name('admin.salesCoordinator');
		Route::get('/salesManger', [ClientsController::class,'salesManger'])->name('admin.salesManger');
		Route::get('/archive', [ClientsController::class,'clientsArchive'])->name('admin.clients.archive');
		Route::get('/contracts', [ClientsController::class,'clientsContracts'])->name('admin.clients.contracts');
		Route::post('/NoAgent/changeAgent', [ClientsController::class,'changeAgent'])->name('admin.noAgentClients.asignAgent');
    });

    Route::group(['prefix'=>'clientSources'], function(){
		Route::get('/', [ClientSourcesController::class,'index'])->name('admin.clients.sources');
		Route::post('/', [ClientSourcesController::class,'store'])->name('admin.clients.sources.store');
		Route::post('/{id}/Edit', [ClientSourcesController::class,'update'])->name('admin.clients.sources.update');
		Route::get('/{id}/Delete', [ClientSourcesController::class,'delete'])->name('admin.clients.sources.delete');
	});

	Route::group(['prefix'=>'followups'], function(){
		Route::get('/nextFollowups', [FollowUpsController::class,'nextFollowups'])->name('admin.nextFollowups');
		Route::get('/', [FollowUpsController::class,'index'])->name('admin.followups');
		Route::post('/NewFollowUp', [FollowUpsController::class,'store'])->name('admin.followups.store');
		Route::get('/{id}/view', [FollowUpsController::class,'view'])->name('admin.followups.details');
		Route::post('/{id}/Edit', [FollowUpsController::class,'update'])->name('admin.followups.update');
		Route::get('/{id}/delete', [FollowUpsController::class,'delete'])->name('admin.followups.delete');
	});

	Route::group(['prefix'=>'reports'], function(){
		Route::get('/accountsReport', [ReportsController::class,'accountsReport'])->name('admin.accountsReport');
		Route::get('/rejectionCauses', [ReportsController::class,'rejectionCauses'])->name('admin.reports.rejectionCauses');
		Route::get('/teamPerformance', [ReportsController::class,'teamPerformance'])->name('admin.reports.teamPerformance');
		Route::get('/clients', [ReportsController::class,'clients'])->name('admin.reports.clients');
		Route::get('/units', [ReportsController::class,'units'])->name('admin.reports.units');
		Route::get('/today', [ReportsController::class,'clientTodayReport'])->name('admin.today.reports');
		Route::get('/month', [ReportsController::class,'clientMonthReport'])->name('admin.month.reports');
	});

    Route::controller(OwnerController::class)->prefix('owner')->group(function(){
        Route::get('/','index')->name('admin.owner');
        Route::get('/getZone/{id}','getZone')->name('admin.owner.getZone');
        Route::post('/store','store')->name('admin.owner.store');
        Route::post('/update/{id}','update')->name('admin.owner.update');
        Route::get('/delete/{id}','delete')->name('admin.owner.delete');
    });

    Route::controller(BrokerController::class)->prefix('broker')->group(function(){
        Route::get('/','index')->name('admin.broker');
        Route::get('/getZone/{id}','getZone')->name('admin.broker.getZone');
        Route::post('/store','store')->name('admin.broker.store');
        Route::post('/update/{id}','update')->name('admin.broker.update');
        Route::get('/delete/{id}','delete')->name('admin.broker.delete');
    });

    Route::prefix('unit')->group(function (){
        Route::controller(UniteTypeController::class)->prefix('type')->group(function (){
            Route::get('/type','index')->name('admin.unitType');
            Route::post('/store','store')->name('admin.unitType.store');
            Route::post('/update/{id}','update')->name('admin.unitType.update');
            Route::get('/delete/{id}','delete')->name('admin.unitType.delete');
        });

        Route::controller(UnitsController::class)->group(function () {
            Route::get('/getZone/{id}','getZone');
            Route::get('/', 'index')->name('admin.units');
            Route::post('/store', 'store')->name('admin.units.store');
            Route::post('/storeExcelUnit', 'storeExcelUnit')->name('admin.units.storeExcelUnit');
            Route::get('/{id}/view', 'view')->name('admin.units.view');
            Route::get('/{id}/edit', 'edit')->name('admin.units.edit');
            Route::post('/{id}/edit', 'update')->name('admin.units.update');
            Route::get('/{id}/DeletePhoto/{photo}/{X}', 'DeleteUnitPhoto')->name('admin.units.deletePhoto');
            Route::get('/{id}/Delete', 'delete')->name('admin.units.delete');
        });
    });

});
