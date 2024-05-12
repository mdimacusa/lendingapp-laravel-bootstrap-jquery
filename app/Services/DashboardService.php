<?php

namespace App\Services;
use DB;

class DashboardService
{
    public function get_data($init_data)
    {
        $query  = DB::table('soa')
        ->whereDate('upcoming_due_date', '>=', $init_data['start_date'])
        ->whereDate('upcoming_due_date', '<=', $init_data['end_date'])
        ->whereIn('status',[0,1])
        ->paginate(5);

        return $query;
    }

}
