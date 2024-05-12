<?php

namespace App\Http\Controllers\Pages\Transactions;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Crypt;
use Carbon\Carbon;
use Illuminate\Support\Facades\RateLimiter;
use App\Events\EventNotification;
use App\Http\Requests\Transaction\StoreBorrowRequest;
use App\Services\BorrowService;
use App\Services\GlobalService;
use Illuminate\Support\Facades\Storage;

class BorrowController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if (!auth()->user()->can('access','borrow')) {
                abort(403,'Unauthorized action.');
            }

            return $next($request);
        });
    }

    public function index(GlobalService $global_service)
    {
        $rates = $global_service->get_rates();
        return view('pages.transactions.borrow',compact('rates'));
    }

    public function notification_response($data)
    {

        // $data = [
        //     'category'  => 'Borrow',
        // ];
        // $this->notif_data($data);
        return response()->json(['status'=>'success','data'=>$data]);
    }
    public function store(StoreBorrowRequest $request,GlobalService $global_service,BorrowService $borrow_service)
    {
        $request->validated();

        DB::beginTransaction();
        try {

            if(RateLimiter::tooManyAttempts('borrow:'.auth()->user()->id, $perMinute = 1)){
                return redirect(route('transactions.borrow'))->with('swal.error','Please wait '.RateLimiter::availableIn('borrow:'.auth()->user()->id).' seconds before next borrow');
            }

            $initialize_data = $borrow_service->initialize_data($request->all(),$global_service,$request->file('pdf_file'),$request->file('valid_id'));

            extract($initialize_data);

            if($company_wallet->count()==0)
            {
                return redirect(route("transactions.borrow"))->with("swal.error","Invalid Fund");
            }
                if($company_wallet->first()->fund<$amount)
                {
                    return redirect(route("transactions.borrow"))->with("swal.error","Insufficient Fund");
                }

                //insert soa
                $soa_id = $borrow_service->insert_soa($initialize_data);

                //upload agreement
                $query = $borrow_service->upload_agreement($initialize_data,$soa_id);

                //insert payment_account
                $query = $borrow_service->insert_payment_account($initialize_data);

                //insert company_wallet_history
                $query = $borrow_service->insert_company_wallet_history($initialize_data);

                //insert notification
                $query = $global_service->borrow_notifications($initialize_data);

                //deduction notification
                $query = $global_service->deducted_company_notifications($initialize_data);

                DB::commit();
                RateLimiter::hit('borrow:'.$processed_by);

            if($query)
            {
                //deduct fund from company wallet
                $query = $global_service->decrement_company_wallet($initialize_data);

                //notification
                $message = "Your borrow request has been processed with REF# ".$reference." the amount of ₱".number_format($amount,2);
                event(new EventNotification("Borrow",$message,$amount,$reference,$fullname,$client->id,$processed_by));

                return redirect(route("transactions.borrow"))->with("swal.success","Successfully Borrowed with REF# ".$reference);
            }

        } catch(Throwable $exception) {
            DB::rollBack();
            return back()->with("swal.error",$exception->getMessage())->withInput();
        }
    }

    public function showClient(GlobalService $global_service,$id)
    {
        $user = $global_service->get_client($id);
        return response()->json($user);
    }

}


