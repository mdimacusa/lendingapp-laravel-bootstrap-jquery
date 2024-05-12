<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Pages\DashboardController;
use App\Http\Controllers\Pages\Transactions\BorrowController;
use App\Http\Controllers\Pages\Transactions\DepositFundController;
use App\Http\Controllers\Pages\Transactions\SoaController;
use App\Http\Controllers\Pages\UserManagement\ClientController;
use App\Http\Controllers\Pages\UserManagement\AdministratorController;
use App\Http\Controllers\Pages\Reports\OverallLoanController;
use App\Http\Controllers\Pages\Reports\FundHistoryController;
use App\Http\Controllers\Pages\Reports\TopBorrowerController;
use App\Http\Controllers\Pages\Reports\CompanyIncomeController;
use App\Http\Controllers\Pages\Settings\RolesAndPermissionsController;
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

Route::get('/', function () {
    return redirect('login');
});
Auth::routes();
Route::middleware('auth')->group(function() {

    Route::get('/dashboard',[DashboardController::class,'index'])->name('dashboard');

    Route::controller(BorrowController::class)->group(function () {
        Route::prefix('transactions')->group(function () {
            Route::get('/borrow', 'index')->name('transactions.borrow');
            Route::post('/borrow/store', 'store')->name('transactions.borrow-store');
            Route::get('/borrow/client/{id?}', 'showClient')->name('transactions.borrow.client');
            Route::get('/borrow/notification/{data}', 'notification_response');
        });
    });

    Route::controller(DepositFundController::class)->group(function () {
        Route::prefix('transactions')->group(function () {
            Route::get('/deposit-fund', 'index')->name('transactions.deposit-fund');
            Route::post('/deposit-fund', 'index')->name('transactions.deposit-fund.filter');
            Route::get('/deposit', 'deposit')->name('transactions.deposit');
            Route::post('/deposit', 'deposit_store')->name('transactions.deposit.store');
        });
    });

    Route::controller(SoaController::class)->group(function () {
        Route::prefix('transactions')->group(function () {
            Route::get('/{reference}', 'print')->name('transactions.statement-of-account.print');
            Route::get('/statement-of-account/{status}', 'index')->name('transactions.statement-of-account');
            Route::post('/statement-of-account/{status}', 'index')->name('transactions.statement-of-account.filter');
            Route::get('/statement-of-account/{status}/{id}', 'show_payment')->name('transactions.statement-of-account.show-payment');
            Route::post('/statement-of-account/{status}/payment/{ref}', 'payment');
            Route::get('/statement-of-account/{status}/payment-history/{ref}', 'show_payment_history');
        });
    });

    Route::controller(ClientController::class)->group(function () {
        Route::prefix('user-management')->group(function () {
            Route::get('/client', 'index')->name('user-management.client');
            Route::post('/client', 'index')->name('user-management.client.filter');
            Route::get('/client/create', 'create')->name('user-management.client.create');
            Route::post('/client/store', 'store')->name('user-management.client.store');
            Route::get('/client/profile/{tab}/{id}', 'index_profile')->name('user-management.client.profile');
            Route::post('/client/profile/{tab}/{id}', 'index_profile')->name('user-management.client.profile.filter');
            Route::post('/client/profile/{tab}/update/{id}', 'update')->name('user-management.client.profile.update');
        });
    });

    Route::controller(AdministratorController::class)->group(function () {
        Route::prefix('user-management')->group(function () {
            Route::get('/administrator', 'index')->name('user-management.administrator');
            Route::post('/administrator', 'index')->name('user-management.administrator.filter');
            Route::get('/administrator/create', 'create')->name('user-management.administrator.create');
            Route::post('/administrator/store', 'store')->name('user-management.administrator.store');
            Route::get('/administrator/profile/{tab}/{id}', 'index_profile')->name('user-management.administrator.profile');
            Route::post('/administrator/profile/{tab}/{id}', 'index_profile')->name('user-management.administrator.profile.filter');
            Route::get('administrator/notification/seen/{id}', 'seen_notification')->name('user-management.administrator.send');
            Route::post('/administrator/profile/{tab}/update/{id}', 'update')->name('user-management.administrator.profile.update');
        });
    });

    Route::prefix('report')->group(function () {
        Route::get('/company-income', [CompanyIncomeController::class,'index'])->name('report.company-income');
        Route::post('/company-income', [CompanyIncomeController::class,'index'])->name('report.company-income.filter');
        Route::get('/company-income/download', [CompanyIncomeController::class, 'download'])->name('report.company-income.export');

        Route::get('/overall-loan', [OverallLoanController::class,'index'])->name('report.overall-loan');
        Route::post('/overall-loan', [OverallLoanController::class,'index'])->name('report.overall-loan.filter');
        Route::get('/overall-loan/download', [OverallLoanController::class,'download'])->name('report.overall-loan.export');

        Route::get('/fund-history', [FundHistoryController::class,'index'])->name('report.fund-history');
        Route::post('/fund-history', [FundHistoryController::class,'index'])->name('report.fund-history.filter');
        Route::get('/fund-history/download', [FundHistoryController::class,'download'])->name('report.fund-history.export');

        Route::get('/top-borrower', [TopBorrowerController::class,'index'])->name('report.top-borrower');
        Route::post('/top-borrower', [TopBorrowerController::class,'index'])->name('report.top-borrower.filter');
        Route::get('/top-borrower/download', [TopBorrowerController::class,'download'])->name('report.top-borrower.export');
    });

    Route::prefix('settings')->group(function () {
        Route::prefix('roles-and-permissions')->group(function () {
            Route::get('/', [RolesAndPermissionsController::class,'index'])->name('settings.roles-and-permissions.index');
            Route::get('/create-permission', [RolesAndPermissionsController::class,'create_permission'])->name('settings.roles-and-permissions.create-permission');
            Route::post('/create-permission', [RolesAndPermissionsController::class,'store_permission'])->name('settings.roles-and-permissions.store-permission');
            Route::get('/create-role', [RolesAndPermissionsController::class,'create_role'])->name('settings.roles-and-permissions.create-role');
            Route::post('/create-role', [RolesAndPermissionsController::class,'store_role'])->name('settings.roles-and-permissions.store-role');
            Route::get('/edit/{role_id}', [RolesAndPermissionsController::class, 'edit'])->name('settings.roles-and-permissions.edit');
            Route::post('/edit/{role_id}', [RolesAndPermissionsController::class, 'update'])->name('settings.roles-and-permissions.update');
        });
    });
});
