<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use App\Services\DashboardService;
class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(DashboardService $dashboard_service)
    {

        $init_data = [
            'start_date' => Carbon::now()->startOfDay(), // Start of the current day
            'end_date'   => Carbon::now()->addDays(5)->endOfDay()
        ];
        $fivedays_before = $dashboard_service->get_data($init_data);

        return view('pages.dashboard',compact('fivedays_before'));
    }

}
