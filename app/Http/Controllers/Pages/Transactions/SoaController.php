<?php

namespace App\Http\Controllers\Pages\Transactions;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Crypt;
use Carbon\Carbon;
use PDF;
use App\Exports\ExportExcel;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Requests\Transaction\StorePaymentRequest;
use App\Services\SoaService;
use App\Services\GlobalService;

class SoaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if (!auth()->user()->can('access','payment')) {
                abort(403,'Unauthorized action.');
            }

            return $next($request);
        });
    }

    public function index(Request $request,$status,SoaService $soa_service)
    {
        $filters=[
            "search"=>$request->search,
            "from"  =>$request->from,
            "to"    =>$request->to,
            "rows"  =>$request->rows,
        ];

        $query = $soa_service->get_soa($filters,$status);

        return view('pages.transactions.soa',compact('query','status','filters'));
    }

    public function payment(StorePaymentRequest $request,GlobalService $global_service,SoaService $soa_service,$status,$reference)
    {
        if (!auth()->user()->can('update','payment')) {
            abort(403, 'Unauthorized action.');
        }

        switch($request->payment_method) {
            case('Standard Payment'):

                $request->validated();

                $initialize_data = $soa_service->initialize_data($request->all(),$reference);

                extract($initialize_data);

                $query = $soa_service->insert_payment($initialize_data);

                $query = $soa_service->update_soa($initialize_data);

                $query = $soa_service->update_payment_account($initialize_data);

                $query = $soa_service->paid_notifications($initialize_data);


                if($query)
                {
                    $msg = $data->fullname." REF# ".$data->reference." Has Been Successfully Paid";
                    // store payment amount to current fund
                    $global_service->payment_company_wallet($initialize_data);
                    $global_service->company_wallet_history($initialize_data);

                    if($soa_service->check_status($initialize_data)->status == 2)
                    {
                        return redirect(route('transactions.statement-of-account',['status'=>$status]))->with("swal.success",$msg);
                    }
                    else
                    {
                        return back()->with("swal.success",$msg);
                    }
                }
            break;

            case('Interest'):
                $request->validated();

                $initialize_data = $soa_service->initialize_data($request->all(),$reference);

                extract($initialize_data);
                //dd($initialize_data);
                $query = $soa_service->insert_payment($initialize_data);

                $query = $soa_service->update_soa($initialize_data);

                $query = $soa_service->update_payment_account($initialize_data);

                $query = $soa_service->paid_notifications($initialize_data);


                if($query)
                {
                    $msg = $data->fullname." REF# ".$data->reference." Has Been Successfully Paid";
                    //increase pentalties
                    $soa_service->increase_penalties_amount($initialize_data);
                    //increase income
                    $soa_service->increase_income($initialize_data);

                    if($soa_service->check_status($initialize_data)->status == 2)
                    {
                        return redirect(route('transactions.statement-of-account',['status'=>$status]))->with("swal.success",$msg);
                    }
                    else
                    {
                        return back()->with("swal.success",$msg);
                    }
                }

            break;

            default:

                $request->validated();

                $initialize_data = $soa_service->initialize_data($request->all(),$reference);
                //dd($initialize_data);
                extract($initialize_data);

                $query = $soa_service->insert_payment($initialize_data);

                $query = $soa_service->update_soa($initialize_data);

                $query = $soa_service->update_payment_account($initialize_data);

                $query = $soa_service->paid_notifications($initialize_data);


                if($query)
                {
                    $msg = $data->fullname." REF# ".$data->reference." Has Been Successfully Paid";

                    if($soa_service->check_status($initialize_data)->status == 2)
                    {
                        return redirect(route('transactions.statement-of-account',['status'=>$status]))->with("swal.success",$msg);
                    }
                    else
                    {
                        return back()->with("swal.success",$msg);
                    }
                }
        }
    }

    public function show_payment(SoaService $soa_service,$status,$reference)
    {
        if (!auth()->user()->can('update','payment')) {
            abort(403, 'Unauthorized action.');
        }

        $data = $soa_service->show_payment(Crypt::decrypt($reference));

        extract($data);

        $remaining_month = ($soa_service->check_transaction($query->reference))?abs($soa_service->count_remaining_mounth($query->reference)-$query->tenurity):$query->tenurity;

        extract($data);

        return view('pages.transactions.payment',compact('query','count_month','status','remaining_month'));
    }

    public function show_payment_history(SoaService $soa_service,$status,$reference)
    {
        $data = $soa_service->show_payment_history(Crypt::decrypt($reference));

        extract($data);

        return view('pages.transactions.payment-history',compact('query','datas','reference','status'));
    }

    public function print(SoaService $soa_service,$reference)
    {
        $data = $soa_service->print_payment_account(Crypt::decrypt($reference));

        extract($data);

        return view('pdf.invoice',compact('transactions','date'));
    }
}
