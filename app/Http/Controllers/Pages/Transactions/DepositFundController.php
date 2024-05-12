<?php

namespace App\Http\Controllers\Pages\Transactions;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Requests\Transaction\StoreDepositRequest;
use App\Services\DepositFundService;
use App\Services\GlobalService;

class DepositFundController extends Controller
{

    public function index(Request $request,GlobalService $global_service,DepositFundService $deposit_fund_service)
    {
        $filters=[
            "search"=>$request->search,
            "from"  =>$request->from,
            "to"    =>$request->to,
            "rows"  =>$request->rows,
        ];

        //extract($filters);

        $query = $deposit_fund_service->get_deposit_fund($filters);

        return view('pages.transactions.deposit-fund',compact('query','filters'));
    }

    public function deposit()
    {
        if (!auth()->user()->can('create','deposit-fund')) {
            abort(403, 'Unauthorized action.');
        }
        return view('pages.transactions.deposit');
    }

    public function deposit_store(StoreDepositRequest $request,GlobalService $global_service,DepositFundService $deposit_service)
    {
        $request->validated();

        DB::beginTransaction();
        try {
            $init_data = [
                'company_wallet'   => $global_service->get_company_wallet(),
                'amount'           => $request->amount,
                'fund_reference'   => 'DEB'.rand(11111,99999).date('Y')
            ];
            if($global_service->get_company_wallet()->count() > 0)
            {
                $global_service->increment_company_wallet($init_data);
            }
            else
            {
                $deposit_service->store_company_wallet($init_data);
            }

            //store company wallet history
            $query = $deposit_service->company_wallet_history($init_data);

            DB::commit();

            if($query) {
                //add notification
                $global_service->added_company_notifications($init_data);

                return redirect(route("transactions.deposit.store"))->with("swal.success","Successfully Deposit");
            }

        } catch(Throwable $exception) {
            DB::rollBack();
            return back()->with("swal.error",$exception->getMessage())->withInput();
        }
    }


}
