<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Redirect;

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

Route::get('/', function () {
    return redirect()->route('login');
});
Route::get('/Support', function () {
    $clients =App\Models\Clients::where('position','delete')->delete();
});
Route::get('SwitchLang/{lang}',function($lang){
    session()->put('Lang',$lang);
    app()->setLocale($lang);
    if (auth()->check()) {
        $user =App\Models\User::find(auth()->user()->id)->update(['language'=>$lang]);
    }
	return Redirect::back();
});

Auth::routes();

Route::get('admin/auth/login',[AdminLoginController::class,'login'])->name('admin.login');
Route::get('publisher/auth/login','publishers\PublisherLoginController@login')->name('publisher.login');

Route::get('convert-payments-to-revenues','system\customOldDataController@ConvertPaymentsToRevenues')->name('system.convertPaymentsToRevenues');
Route::get('ConvertTransfers','system\customOldDataController@ConvertTransfers')->name('system.ConvertTransfers');
Route::get('refineExpensesDays','system\customOldDataController@refineExpensesDays')->name('system.refineExpensesDays');
Route::get('refineRevenuesDays','system\customOldDataController@refineRevenuesDays')->name('system.refineRevenuesDays');
Route::get('refineUserEmploymentDate','system\customOldDataController@refineUserEmploymentDate')->name('system.refineUserEmploymentDate');
Route::get('refineUserDeductions','system\customOldDataController@refineUserDeductions')->name('system.refineUserDeductions');
Route::get('refineContractPeyments','system\customOldDataController@refineContractPeyments')->name('system.refineContractPeyments');
Route::get('refineContracts','system\customOldDataController@refineContracts')->name('system.refineContracts');


Route::get('AdminPanel/changeTheme','admin\AdminPanelController@changeTheme')->name('changeTheme');
