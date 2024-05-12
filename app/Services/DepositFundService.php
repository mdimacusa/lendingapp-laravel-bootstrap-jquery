<?php

namespace App\Services;
use DB;

class DepositFundService
{
    public function get_deposit_fund($init_data)
    {
        $search = $init_data['search'];

        if($init_data['rows'] == "All")
        {
            $query  = DB::table('company_wallet_history')
            ->select('company_wallet_history.*','users.name')
            ->join('users','users.id','=','company_wallet_history.users_id')
            ->when(!empty($init_data['search']),function($query)use($search){
                $query->where(function($query) use($search){
                    $query->orWhere('company_wallet_history.reference','like','%'.$search.'%')
                        ->orWhere('company_wallet_history.amount','like','%'.$search.'%');
                });
            })
            ->whereBetween('company_wallet_history.created_at',[($init_data['from'] ?? "0000-00-00")." 00:00:00",($init_data['fr'] ?? date("Y-m-d"))." 23:59:59"])
            ->where('company_wallet_history.status','CREDIT')
            ->orderBy('company_wallet_history.created_at','DESC')
            ->get(10);
        }
        else
        {
            $query = DB::table('company_wallet_history')
            ->select('company_wallet_history.*','users.name')
            ->join('users','users.id','=','company_wallet_history.users_id')
            ->when(!empty($search),function($query)use($search){
                $query->where(function($query) use($search){
                    $query
                        ->orWhere('company_wallet_history.reference','like','%'.$search.'%')
                        ->orWhere('company_wallet_history.amount','like','%'.$search.'%');
                });
            })
            ->whereBetween('company_wallet_history.created_at',[($filters['from'] ?? "0000-00-00")." 00:00:00",($filters['to'] ?? date("Y-m-d"))." 23:59:59"])
            ->where('company_wallet_history.status','CREDIT')
            ->orderBy('company_wallet_history.created_at','DESC')
            ->paginate($filters['rows'] ?? 10);
        }

        return $query;
    }

    public function store_company_wallet($init_data)
    {
        $query = DB::table('company_wallet')
        ->insert([
            'fund'=> $init_data['amount'],
        ]);
        return $query;
    }

    public function company_wallet_history($init_data)
    {
        $query = DB::table('company_wallet_history')->insert([
            'users_id'  => auth()->user()->id,
            'reference' => $init_data['fund_reference'],
            'amount'    => $init_data['amount'],
        ]);
        return $query;
    }

}
