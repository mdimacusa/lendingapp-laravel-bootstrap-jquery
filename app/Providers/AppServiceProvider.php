<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
use Carbon\Carbon;
use DB;
use App\Services\GlobalService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrapFive();
        View::composer('*', function ($view) {
            if(auth()->user()==NULL){


                $global                 = new GlobalService();
                $currentDate            = Carbon::now('Asia/Manila');
                $today_transaction      = $global->get_today_transaction($currentDate);
                $overall_transaction    = $global->get_overall_transaction();
                $today_income           = $global->get_today_income($currentDate);
                $overall_income         = $global->get_overall_income();
                $total_fund             = $global->get_total_fund();
                $overall_used_fund      = $global->get_overall_used_fund();
                $total_unpaid           = $global->get_total_unpaid();
                $total_partially_paid   = $global->get_total_partially_paid();
                $total_paid             = $global->get_total_paid();

                $view->with('today_transaction', $today_transaction)
                ->with('overall_transaction', $overall_transaction)
                ->with('today_income',$today_income)
                ->with('overall_income',$overall_income)
                ->with('total_fund',$total_fund)
                ->with('overall_used_fund', $overall_used_fund)
                ->with('total_unpaid', $total_unpaid)
                ->with('total_partially_paid', $total_partially_paid)
                ->with('total_paid', $total_paid);

            }else{

                $global                 = new GlobalService();
                $currentDate            = Carbon::now('Asia/Manila');
                $today_transaction      = $global->get_today_transaction($currentDate);
                $overall_transaction    = $global->get_overall_transaction();
                $today_income           = $global->get_today_income($currentDate);
                $overall_income         = $global->get_overall_income();
                $total_fund             = $global->get_total_fund();
                $overall_used_fund      = $global->get_overall_used_fund();
                $total_unpaid           = $global->get_total_unpaid();
                $total_partially_paid   = $global->get_total_partially_paid();
                $total_paid             = $global->get_total_paid();
                $borrow_notif_credit    = $global->get_borrow_notif_credit();
                $fund_notift            = $global->get_fund_notift();
                $notification_count     = $global->get_notification_count();
                $unpaid                 = $global->get_unpaid();
                $partially_paid         = $global->get_partially_paid();
                $fully_paid             = $global->get_fully_paid();
                $client                 = $global->get_clients();
                $administrator          = $global->get_administrator();

                $view->with('today_transaction', $today_transaction)
                ->with('overall_transaction', $overall_transaction)
                ->with('today_income',$today_income)
                ->with('overall_income',$overall_income)
                ->with('total_fund',$total_fund)
                ->with('overall_used_fund', $overall_used_fund)
                ->with('total_unpaid', $total_unpaid)
                ->with('total_partially_paid', $total_partially_paid)
                ->with('total_paid', $total_paid)
                ->with('borrow_notif_credit', $borrow_notif_credit)
                ->with('fund_notift', $fund_notift)
                ->with('notification_count', $notification_count)
                ->with('unpaid', $unpaid)
                ->with('partially_paid', $partially_paid)
                ->with('fully_paid', $fully_paid)
                ->with('client', $client)
                ->with('administrator', $administrator);
            }

        });
    }
}
