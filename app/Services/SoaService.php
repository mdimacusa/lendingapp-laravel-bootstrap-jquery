<?php

namespace App\Services;
use DB;
use Carbon\Carbon;
use App\Services\GlobalService;

class SoaService extends GlobalService
{

    public function initialize_data($data,$reference)
    {
        $data = (object)$data;

        if($data->payment_method == "Standard Payment")
        {
            $init_data = [
                'payment_method'    => $data->payment_method,
                'processed_by'      => auth()->user()->id,
                'data'              => $this->get_payment_account($reference),
                'remarks'           => $data->remarks,
                'payment_date'      => $data->payment_date,
                'payment_for'       => $data->payment_for,
                'fund_reference'    => $reference,
                'company_wallet'    => $this->get_company_wallet(),
            ];

            extract($init_data);
            $init_data['remaining_month']   = ($this->check_transaction($reference))?abs($this->count_remaining_mounth($reference)-(int)$data->tenurity)-1:(int)$data->tenurity-1;
            $init_data['amount']            = ($init_data['remaining_month']==0)?$data->loan_outstanding:$data->monthly * $payment_for;
            $init_data['payment_amount']    = round(($data->monthly * $payment_for)-($data->interest / $data->tenurity) * $payment_for,2);
            $init_data['monthly']           = round($data->monthly,2);
            $init_data['loan_outstanding']  = (($data->loan_outstanding - $init_data['amount']) < 3) ? 0 : $data->loan_outstanding - $init_data['amount'];
            $init_data['upcoming_due_date'] = (new Carbon(date('Y-m-d',strtotime($data->upcoming_due_date))))->addMonths($payment_for);
            $init_data['to']                = Carbon::parse(date($init_data['upcoming_due_date']))->subMonths(1);
            $init_data['from']              = Carbon::parse(date("Y-m-d",strtotime($data->due_date)));
            $init_data['count_month']       = $init_data['to']->diffInMonths($init_data['from']);
            $init_data['new_monthly']       = ($init_data['to']->diffInMonths($init_data['from'])>0) ? $init_data['loan_outstanding'] / $init_data['count_month'] : 0.0;

        }
        elseif($data->payment_method == "Interest")
        {

            $init_data = [
                'payment_method'    => $data->payment_method,
                'processed_by'      => auth()->user()->id,
                'data'              => $this->get_payment_account($reference),
                'remarks'           => $data->remarks,
                'payment_date'      => $data->payment_date,
                'payment_for'       => 1,
            ];

            extract($init_data);

            $init_data['remaining_month']       = ($this->check_transaction($reference))?abs($this->count_remaining_mounth($reference)-(int)$data->tenurity)-1:(int)$data->tenurity-1;
            $init_data['amount']                = round(($this->get_penalties_amount($init_data)>0)?(($data->monthly*$data->rate)+$this->get_penalties_amount($init_data)):$data->monthly*$data->rate,2);
            $init_data['monthly']               = round($data->monthly,2);
            $init_data['rate']                  = $data->rate;
            $init_data['penalty_interest']      = round($data->monthly*$data->rate,2);
            $init_data['total_payment']         = $init_data['amount'];
            $init_data['loan_outstanding']      = round($data->loan_outstanding,2);
            $init_data['upcoming_due_date']     = (new Carbon(date('Y-m-d',strtotime($data->upcoming_due_date))))->addMonths($payment_for);

        }
        else
        {
            $init_data = [
                'payment_method'    => $data->payment_method,
                'processed_by'      => auth()->user()->id,
                'data'              => $this->get_payment_account($reference),
                'remarks'           => $data->remarks,
                'payment_date'      => $data->payment_date,
                'fund_reference'    => $reference,
                'company_wallet'    => $this->get_company_wallet(),
            ];

            extract($init_data);
            $init_data['payment_for']           = ($this->check_transaction($reference))?abs($this->count_remaining_mounth($reference)-$data->tenurity):$data->tenurity;
            $init_data['remaining_month']       = $init_data['payment_for'];
            $init_data['amount']                = round($data->loan_outstanding,2);
            $init_data['payment_amount']        = round(($data->monthly * $init_data['payment_for'])-($data->interest / $data->tenurity) * $init_data['payment_for'],2);
            $init_data['monthly']               = round($data->monthly,2);
            $init_data['rate']                  = $data->rate;
            $init_data['penalty_interest']      = 0;
            $init_data['total_payment']         = $init_data['amount'];
            $init_data['loan_outstanding']      = 0;
            $init_data['upcoming_due_date']     = (new Carbon(date('Y-m-d',strtotime($data->upcoming_due_date))))->addMonths($init_data['payment_for']);
        }
        return $init_data;
    }

    public function get_soa($init_data,$status)
    {
        $search = $init_data['search'];
        $data = DB::table('soa')
        ->select('soa.*','client.unique_id','users.name')
        ->join('client','client.id','=','soa.client_id')
        ->join('users','users.id','=','soa.users_id')
        ->when(!empty($search),function($query)use($search){
            $query->where(function($query) use($search){
                $query->orWhere('soa.reference','like','%'.$search.'%')
                ->orWhere('soa.amount','like','%'.$search.'%');
            });
        })
        ->whereBetween('soa.disbursement_date',[($init_data['from'] ?? "0000-00-00")." 00:00:00",($init_data['to'] ?? date("Y-m-d"))." 23:59:59"]);

        if ($status=="fully-paid")
        {
            $data->where('soa.status',2);
        }
        elseif ($status=="partially-paid")
        {
            $data->where('soa.status',1);
        }
        else
        {
            $data->where('soa.status',0);
        }

        if($init_data['rows'] == "All")
        {
            $query = $data->orderBy('soa.disbursement_date','DESC')->get();
        }
        else
        {
            $query = $data->orderBy('soa.disbursement_date','DESC')->paginate($init_data['rows'] ?? 10);
        }

        return $query;
    }
    public function get_payment_account($reference)
    {
        $query = DB::table('payment_account')
        ->where('reference',$reference)
        ->first();
        return $query;
    }

    public function show_payment($reference)
    {
        $query = DB::table('payment_account')
                ->select('payment_account.*','client.unique_id')
                ->join('client','client.id','=','payment_account.client_id')
                ->where('payment_account.reference',$reference)->first();
        $to   = Carbon::parse(date('Y-m-d',strtotime($query->upcoming_due_date)));
        $from = Carbon::parse(date("Y-m-d",strtotime($query->due_date)));
        $count_month = $to->diffInMonths($from)+1;

        $data = [
            'query'         =>  $query,
            'to'            =>  $to,
            'from'          =>  $from,
            'count_month'   =>  $count_month,
        ];

        return $data;
    }

    public function show_payment_history($reference)
    {

        $query = DB::table('payment_account')
        ->select('payment_account.*','client.unique_id')
        ->join('client','client.id','=','payment_account.client_id')
        ->where('payment_account.reference',$reference)
        ->first();

        $datas = DB::table('payment')
        ->select('payment.*','users.name','payment_account.account_no')
        ->join('users','users.id','=','payment.users_id')
        ->join('payment_account','payment_account.reference','=','payment.reference')
        ->where('payment.reference',$reference)
        ->paginate(10);

        $data = [
            'query'     =>  $query,
            'datas'     =>  $datas,
            'reference' =>  $reference,
        ];

        return $data;
    }


    public function insert_payment($init_data)
    {
        extract($init_data);

        if($payment_method=="Standard Payment")
        {
            $query = DB::table('payment')
            ->insert([
                'client_id'         => $data->client_id,
                'users_id'          => $processed_by,
                'fullname'          => $data->fullname,
                'rate'              => $data->rate,
                'amount'            => $data->amount,
                'tenurity'          => $data->tenurity,
                'interest'          => $loan_outstanding=="0.0"?"0.0":$data->interest,
                'loan_outstanding'  => round($loan_outstanding,2),
                'monthly'           => round($loan_outstanding=="0.0"?"0.0":$monthly,2),
                'reference'         => $data->reference,
                'status'            => $loan_outstanding=="0.0"?"2":"1",
                'remarks'           => $remarks,
                'payment_amount'    => $amount,
                'payment_for'       => $loan_outstanding=="0.0"?"Completed":$payment_for,
                'payment_method'    => $payment_method,
                'disbursement_date' => $data->disbursement_date,
                'last_payment_date' => $payment_date,
                'upcoming_due_date' => $loan_outstanding=="0.0"?NULL:$upcoming_due_date,
                'due_date'          => $data->due_date,
            ]);
        }
        else
        {

            $query = DB::table('payment')
            ->insert([
                'client_id'         => $data->client_id,
                'users_id'          => $processed_by,
                'fullname'          => $data->fullname,
                'rate'              => $data->rate,
                'amount'            => $data->amount,
                'tenurity'          => $data->tenurity,
                'interest'          => $loan_outstanding=="0.0"?"0.0":$data->interest,
                'loan_outstanding'  => round($loan_outstanding,2),
                'monthly'           => round($loan_outstanding=="0.0"?"0.0":$monthly,2),
                'reference'         => $data->reference,
                'status'            => $loan_outstanding=="0.0"?"2":"1",
                'remarks'           => $remarks,
                'payment_amount'    => round($total_payment,2),
                'balance_amount'    => 0,
                'penalty_interest'  => round($penalty_interest,2),
                'payment_for'       => $loan_outstanding=="0.0"?"Completed":$payment_for,
                'payment_method'    => $payment_method,
                'disbursement_date' => $data->disbursement_date,
                'last_payment_date' => $payment_date,
                'upcoming_due_date' => $loan_outstanding=="0.0"?NULL:$upcoming_due_date,
                'due_date'          => $data->due_date,
            ]);
        }

        return $query;
    }

    public function update_soa($init_data)
    {
        extract($init_data);

        if($payment_method=="Standard Payment")
        {
            $query = DB::table('soa')
            ->where('reference',$data->reference)
            ->update([
                'users_id'                 => $processed_by,
                'status'                   => $loan_outstanding=="0.0"?"2":"1",
                'remarks'                  => $remarks,
                'payment_amount'           => $amount,
                'payment_method'           => $payment_method,
                'last_payment_date'        => $payment_date,
                'current_loan_outstanding' => $loan_outstanding,
                'upcoming_due_date'        => $loan_outstanding=="0.0"?NULL:$upcoming_due_date,
            ]);
        }
        else
        {
            $query = DB::table('soa')
            ->where('reference',$data->reference)
            ->update([
                'users_id'                  => $processed_by,
                'status'                    => $loan_outstanding=="0.0"?"2":"1",
                'remarks'                   => $remarks,
                'payment_amount'            => round($total_payment,2),
                'payment_method'            => $payment_method,
                'monthly'                   => round($loan_outstanding=="0.0"?"0.0":$monthly,2),
                'current_loan_outstanding'  => $loan_outstanding,
                'last_payment_date'         => $payment_date,
                'upcoming_due_date'         => $loan_outstanding=="0.0"?NULL:$upcoming_due_date,
            ]);
        }

        return $query;
    }

    public function update_payment_account($init_data)
    {
        extract($init_data);

        if($payment_method=="Standard Payment")
        {
            $query = DB::table('payment_account')
            ->where('account_no',$data->account_no)
            ->update([
                'client_id'                 => $data->client_id,
                'users_id'                  => $processed_by,
                'fullname'                  => $data->fullname,
                'rate'                      => $data->rate,
                'amount'                    => $data->amount,
                'tenurity'                  => $data->tenurity,
                'interest'                  => $loan_outstanding=="0.0"?"0.0":$data->interest,
                'loan_outstanding'          => round($loan_outstanding,2),
                'monthly'                   => round($loan_outstanding=="0.0"?"0.0":$monthly,2),
                'reference'                 => $data->reference,
                'status'                    => $loan_outstanding=="0.0"?"2":"1",
                'remarks'                   => $remarks,
                'payment_amount'            => $amount,
                'additional_interest_amount'=> 0.0,
                'payment_for'               => $loan_outstanding=="0.0"?"Completed":"1",
                'payment_method'            => $payment_method,
                'disbursement_date'         => $data->disbursement_date,
                'last_payment_date'         => $payment_date,
                'upcoming_due_date'         => $loan_outstanding=="0.0"?NULL:$upcoming_due_date,
                'due_date'                  => $data->due_date,
            ]);
        }
        elseif($payment_method == "Interest")
        {

            $query = DB::table('payment_account')
            ->where('account_no',$data->account_no)
            ->update([
                'client_id'                 => $data->client_id,
                'users_id'                  => $processed_by,
                'fullname'                  => $data->fullname,
                'rate'                      => $data->rate,
                'amount'                    => $data->amount,
                'tenurity'                  => $data->tenurity,
                'interest'                  => $loan_outstanding=="0.0"?"0.0":$data->interest,
                'loan_outstanding'          => round($loan_outstanding,2),
                'monthly'                   => round($loan_outstanding=="0.0"?"0.0":$monthly,2),
                'reference'                 => $data->reference,
                'status'                    => $loan_outstanding=="0.0"?"2":"1",
                'remarks'                   => $remarks,
                'payment_amount'            => round($total_payment,2),
                'balance_amount'            => 0,
                'penalty_interest'          => round($penalty_interest,2),
                'payment_for'               => $loan_outstanding=="0.0"?"Completed":$payment_for,
                'payment_method'            => $payment_method,
                'disbursement_date'         => $data->disbursement_date,
                'last_payment_date'         => $payment_date,
                'upcoming_due_date'         => $loan_outstanding=="0.0"?NULL:$upcoming_due_date,
                'due_date'                  => $data->due_date,
            ]);


        }
        else
        {
            $query = DB::table('payment_account')
            ->where('account_no',$data->account_no)
            ->update([
                'client_id'                 => $data->client_id,
                'users_id'                  => $processed_by,
                'fullname'                  => $data->fullname,
                'rate'                      => $data->rate,
                'amount'                    => $data->amount,
                'tenurity'                  => $data->tenurity,
                'interest'                  => $loan_outstanding=="0.0"?"0.0":$data->interest,
                'loan_outstanding'          => round($loan_outstanding,2),
                'monthly'                   => round($loan_outstanding=="0.0"?"0.0":$monthly,2),
                'reference'                 => $data->reference,
                'status'                    => $loan_outstanding=="0.0"?"2":"1",
                'remarks'                   => $remarks,
                'payment_amount'            => round($total_payment,2),
                'balance_amount'            => 0,
                'penalty_interest'          => round($penalty_interest,2),
                'payment_for'               => $loan_outstanding=="0.0"?"Completed":$payment_for,
                'payment_method'            => $payment_method,
                'disbursement_date'         => $data->disbursement_date,
                'last_payment_date'         => $payment_date,
                'upcoming_due_date'         => $loan_outstanding=="0.0"?NULL:$upcoming_due_date,
                'due_date'                  => $data->due_date,
            ]);
        }

        return $query;
    }

    public function paid_notifications($init_data)
    {
        extract($init_data);

        if($payment_method=="Standard Payment")
        {
            $query = DB::table('notifications')
            ->insert([
                'client_id'  => $data->client_id,
                'users_id'   => $processed_by,
                'name'       => $data->fullname,
                'reference'  => $data->reference,
                'category'   => 'Borrow',
                'type'       => 'CREDIT',
                'amount'     => $amount,
                'message'    => "The amount of ₱".number_format($amount,2)." with REF# ".$data->reference." Has Been Successfully Paid",
            ]);
        }
        else
        {
            $query = DB::table('notifications')->insert([
                'client_id'  => $data->client_id,
                'users_id'   => $processed_by,
                'name'       => $data->fullname,
                'reference'  => $data->reference,
                'category'   => 'Borrow',
                'type'       => 'CREDIT',
                'amount'     => round($total_payment,2),
                'message'    => "The amount of ₱".number_format(round($total_payment,2),2)." with REF# ".$data->reference." Has Been Successfully Paid",
            ]);
        }

        return $query;
    }

    public function check_status($init_data)
    {
        extract($init_data);

        $query = DB::table('payment_account')
        ->where('account_no',$data->account_no)
        ->first();

        return $query;
    }

    public function increase_income($init_data)
    {
        extract($init_data);

        $query = DB::table('payment_account')
        ->where(['account_no' => $data->account_no])
        ->increment('income',$penalty_interest);

        return $query;
    }
    public function increase_penalties_amount($init_data)
    {
        extract($init_data);

        $query = DB::table('payment_account')
        ->where(['account_no' => $data->account_no])
        ->increment('additional_interest_amount',$penalty_interest);

        return $query;
    }

    public function get_penalties_amount($init_data)
    {
        extract($init_data);

        $query = DB::table('payment_account')
        ->where(['account_no' => $data->account_no])->first()->additional_interest_amount;

        return $query;
    }

    public function print_payment_account($reference)
    {
        $transactions = DB::table('payment_account')
        ->select('payment_account.*','client.first_name','users.name')
        ->join('client','client.id','=','payment_account.client_id')
        ->join('users','users.id','=','payment_account.users_id')
        ->where(['reference'=>$reference])
        ->first();
        $date = Carbon::createFromFormat('Y-m-d H:i:s', $transactions->disbursement_date)->format('d/m/Y');

        $data = [
            'transactions'    => $transactions,
            'date'            => $date,
        ];

        return $data;
    }

    public function check_transaction($reference)
    {
        $data = DB::table('payment')->where(['reference' => $reference])->exists();

        return $data;
    }
    public function count_remaining_mounth($reference)
    {
        $data = DB::table('payment')->where(['reference' => $reference])->count();

        return $data;
    }

}
