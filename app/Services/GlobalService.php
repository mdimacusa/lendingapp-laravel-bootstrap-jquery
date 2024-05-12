<?php

namespace App\Services;
use Crypt;
use DB;

class GlobalService
{
    public function borrow_notifications($init_data)
    {
        $query = DB::table('notifications')->insert([
            'client_id'  => $init_data['client']->id,
            'users_id'   => $init_data['processed_by'],
            'name'       => $init_data['fullname'],
            'reference'  => $init_data['reference'],
            'category'   => 'Borrow',
            'type'       => 'DEBIT',
            'amount'     => $init_data['amount'],
            'message'    => "The amount of ₱".number_format($init_data['amount'],2)." with REF# ".$init_data['reference']." Has Been Successfully Borrowed",
        ]);

        return $query;
    }


    public function added_notifications($init_data)
    {
        $query = DB::table('notifications')->insert([
            'client_id'  => $init_data['client']->id,
            'users_id'   => $init_data['processed_by'],
            'name'       => $init_data['fullname'],
            'reference'  => $init_data['reference'],
            'category'   => 'Borrow',
            'type'       => 'DEBIT',
            'amount'     => $init_data['amount'],
            'message'    => "The amount of ₱".number_format($init_data['amount'],2)." with REF# ".$init_data['reference']." Has Been Successfully Borrowed",
        ]);

        return $query;
    }
    public function added_company_notifications($init_data)
    {
        $query = DB::table('notifications')->insert([
            'users_id'   => auth()->user()->id,
            'name'       => auth()->user()->name,
            'reference'  => $init_data['fund_reference'],
            'category'   => 'Fund',
            'type'       => 'CREDIT',
            'amount'     => $init_data['amount'],
            'message'    => "The amount of ₱".number_format($init_data['amount'],2)." Has Been Successfully Added with REF# ".$init_data['fund_reference']." to the Company Fund",
        ]);

        return $query;
    }

    public function deducted_company_notifications($init_data)
    {
        $query = DB::table('notifications')->insert([
            'client_id'  => $init_data['client']->id,
            'users_id'   => $init_data['processed_by'],
            'name'       => $init_data['fullname'],
            'reference'  => $init_data['fund_reference'],
            'category'   => 'Fund',
            'type'       => 'DEBIT',
            'amount'     => $init_data['amount'],
            'message'    => "The amount of ₱".number_format($init_data['amount'],2)." Has Been Successfully Deducted with REF# ".$init_data['fund_reference']." to the Company Fund",
        ]);

        return $query;
    }
    public function increment_company_wallet($init_data)
    {
        $query = DB::table('company_wallet')
        ->where('unique_id',$init_data['company_wallet']->first()->unique_id)
        ->increment('fund',$init_data['amount']);
        return $query;
    }

    public function payment_company_wallet($init_data)
    {
        $query = DB::table('company_wallet')
        ->where('unique_id',$init_data['company_wallet']->first()->unique_id)
        ->increment('fund',$init_data['payment_amount']);
        return $query;
    }
    public function company_wallet_history($init_data)
    {
        $query = DB::table('company_wallet_history')->insert([
            'users_id'  => auth()->user()->id,
            'reference' => $init_data['fund_reference'],
            'amount'    => $init_data['payment_amount'],
        ]);
        return $query;
    }

    public function decrement_company_wallet($init_data)
    {
        $query = DB::table('company_wallet')
        ->where('unique_id',$init_data['company_wallet']->first()->unique_id)
        ->decrement('fund',$init_data['amount']);

        return $query;
    }

    public function get_today_transaction($currentdate)
    {
        $query = DB::table('payment')
        ->whereDate('last_payment_date',$currentdate)
        ->sum('payment_amount');
        return round($query);
    }
    public function get_overall_transaction()
    {
        $query = DB::table('payment')
        ->sum('payment_amount');
        return round($query);
    }
    public function get_today_income($currentdate)
    {
        $query = DB::table('payment_account')
        ->whereDate('last_payment_date',$currentdate)
        ->where('status',2)
        ->sum('income');
        return round($query);
    }
    public function get_overall_income()
    {
        $query = DB::table('payment_account')
        ->where('status',2)
        ->sum('income');
        return round($query);
    }
    public function get_total_fund()
    {
        $query = DB::table('company_wallet')
        ->sum('fund');
        return round($query);
    }
    public function get_overall_used_fund()
    {
        $query = DB::table('payment_account')
        ->sum('amount');
        return $query;
    }
    public function get_total_unpaid()
    {
        $query = DB::table('payment_account')
        ->where('status',0)->count();
        return $query;
    }
    public function get_total_partially_paid()
    {
        $query = DB::table('payment_account')
        ->where('status',1)
        ->count();
        return $query;
    }
    public function get_total_paid()
    {
        $query = DB::table('payment_account')
        ->where('status',2)
        ->count();
        return $query;
    }
    public function get_borrow_notif_credit()
    {
        $query = DB::table('notifications')
        ->where(['users_id' => auth()->user()->id,'category'=>'Borrow','_seen' => 'No'])
        ->orderBy('created_at','DESC')
        ->orderBy('id','DESC')
        ->get();
        return $query;
    }
    public function get_fund_notift()
    {
        $query = DB::table('notifications')
        ->where(['users_id' => auth()->user()->id,'category'=>'Fund','_seen' => 'No'])
        ->orderBy('created_at','DESC')
        ->orderBy('id','DESC')
        ->get();
        return $query;
    }
    public function get_notification_count()
    {
        $query = DB::table('notifications')
        ->where(['users_id' => auth()->user()->id,'_seen' => 'No'])
        ->count();
        return $query;
    }
    public function get_unpaid()
    {
        $query = DB::table('soa')
        ->where('status',0)
        ->count();
        return $query;
    }
    public function get_partially_paid()
    {
        $query = DB::table('soa')
        ->where('status',1)
        ->count();
        return $query;
    }
    public function get_fully_paid()
    {
        $query = DB::table('soa')
        ->where('status',2)
        ->count();
        return $query;
    }
    public function get_clients()
    {
        $query = DB::table('client')
        ->where('status','ACTIVE')
        ->count();
        return $query;
    }
    public function get_administrator()
    {
        $query = DB::table('users')
        ->where('role','ADMINISTRATOR')
        ->count();
        return $query;
    }
    public function get_company_wallet()
    {
        $query = DB::table('company_wallet')->get();
        return $query;
    }
    public function get_rates()
    {
        $query = DB::table('rates')->get();
        return $query;
    }
    public function get_rate($rate)
    {
        $query = DB::table('rates')->where('id',$rate)->first();
        return $query;
    }
    public function get_client($uniqueid)
    {
        $query = DB::table('client')->where(['unique_id'=>$uniqueid,'status'=>'ACTIVE'])->first();
        return $query;
    }
}
