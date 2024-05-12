<?php

namespace App\Services;
use DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
class BorrowService
{
    public function initialize_data($data,$global_service,$pdf_file,$valid_id)
    {
        $data = (object)$data;

        $init_data = [
            'uniqueid'           => $data->unique_id??'',
            'processed_by'       => auth()->user()->id ,
            'client'             => $global_service->get_client($data->unique_id),
            'rate'               => $global_service->get_rate($data->rate)->rate,
            'tenurity'           => $data->tenurity,
            'amount'             => $data->amount,
            'reference'          => 'AML'.rand(11111,99999).date('Y'),
            'fund_reference'     => 'DEB'.rand(11111,99999).date('Y'),
            'disbursement_date'  => $data->disbursement_date,
            'company_wallet'     => $global_service->get_company_wallet(),
            'pdf_file'           => $pdf_file,
            'valid_id'           => $valid_id,
        ];

        extract($init_data);

        $init_data['fullname']           = !empty($client->middle_name)?$client->first_name.' '.$client->middle_name.' '.$client->surname:$client->first_name.' '.$client->surname;
        $init_data['partial_interest']   = $amount * $rate;
        $init_data['interest']           = ($amount*$rate)*$tenurity;
        $init_data['loan_outstanding']   = $init_data['interest']+$amount;
        $init_data['monthly']            = $init_data['loan_outstanding']/$tenurity;
        $init_data['upcoming_due_date']  = (new Carbon($disbursement_date))->addMonth();
        $init_data['due_date']           = (new Carbon($disbursement_date))->addMonths($tenurity);

        return $init_data;
    }
    public function insert_soa($init_data)
    {
        $query = DB::table('soa')->insertGetId([
            'client_id'         => $init_data['client']->id,
            'users_id'          => $init_data['processed_by'],
            'fullname'          => $init_data['fullname'],
            'rate'              => $init_data['rate'],
            'amount'            => $init_data['amount'],
            'tenurity'          => $init_data['tenurity'],
            'interest'          => $init_data['interest'],
            'loan_outstanding'  => $init_data['loan_outstanding'],
            'monthly'           => $init_data['monthly'],
            'reference'         => $init_data['reference'],
            'disbursement_date' => $init_data['disbursement_date'],
            'upcoming_due_date' => $init_data['upcoming_due_date'],
            'due_date'          => $init_data['due_date'],
            'status'            => 0,
        ]);
        return $query;
    }
    public function insert_payment_account($init_data)
    {
        $query = DB::table('payment_account')->insert([
            'account_no'        => 'ACC'.rand(11111,99999).date('Y'),
            'client_id'         => $init_data['client']->id,
            'users_id'          => $init_data['processed_by'],
            'fullname'          => $init_data['fullname'],
            'rate'              => $init_data['rate'],
            'amount'            => $init_data['amount'],
            'tenurity'          => $init_data['tenurity'],
            'interest'          => $init_data['interest'],
            'income'            => $init_data['interest'],
            'loan_outstanding'  => $init_data['loan_outstanding'],
            'monthly'           => $init_data['monthly'],
            'reference'         => $init_data['reference'],
            'disbursement_date' => $init_data['disbursement_date'],
            'upcoming_due_date' => $init_data['upcoming_due_date'],
            'due_date'          => $init_data['due_date'],
            'status'            => 0,
        ]);
        return $query;
    }
    public function insert_company_wallet_history($init_data)
    {
        $query = DB::table('company_wallet_history')->insert([
            'users_id'  => $init_data['processed_by'],
            'reference' => $init_data['fund_reference'],
            'amount'    => $init_data['amount'],
            'status'    => 'DEBIT',
        ]);
        return $query;
    }

    public function upload_agreement($init_data,$soa_id)
    {
        $data = (object)$init_data;

        $pdf = $data->pdf_file;
        $pdfName = 'pdf_agreement_'.date("Ymd_His").'.'.$pdf->getClientOriginalExtension();
        $pdf->storeAs('public/agreement/pdf/'.$data->client->unique_id, $pdfName);

        $valid_id = $data->valid_id;
        $validIDName = 'valid_id_agreement_'.date("Ymd_His").'.'.$valid_id->getClientOriginalExtension();
        $valid_id->storeAs('public/agreement/valid_id/'.$data->client->unique_id, $validIDName);


        $query = DB::table('agreement_files')
        ->insert([
            'client_id' => $data->client->id,
            'soa_id'    => $soa_id,
            'pdf'       => $pdfName,
            'valid_id'  => $validIDName,
        ]);
        return $data;
    }


}
