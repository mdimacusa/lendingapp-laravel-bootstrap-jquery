<?php

namespace App\Http\Controllers\Pages\Reports;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Exports\ExportExcel;
use Maatwebsite\Excel\Facades\Excel;
class FundHistoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if (!auth()->user()->can('access','report')) {
                abort(403,'Unauthorized action.');
            }

            return $next($request);
        });
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $filters=[
            "search"=>$request->search,
            "from"  =>$request->from,
            "to"    =>$request->to,
            "rows"  =>$request->rows,
        ];
        extract($filters);

        if($rows == "All"){

        $query = DB::table('company_wallet_history')
        ->select('company_wallet_history.*','users.name')
        ->join('users','users.id','=','company_wallet_history.users_id')
        ->when(!empty($search),function($query)use($search){
            $query->where(function($query) use($search){
                $query->orWhere('company_wallet_history.reference','like','%'.$search.'%')
                ->orWhere('company_wallet_history.amount','like','%'.$search.'%')
                ->orWhere('users.name','like','%'.$search.'%');
            });
        })
        ->whereBetween('company_wallet_history.created_at',[($filters['from'] ?? "0000-00-00")." 00:00:00",($filters['to'] ?? date("Y-m-d"))." 23:59:59"])
        ->orderBy('company_wallet_history.created_at','DESC')
        ->get();

        }else{

        $query = DB::table('company_wallet_history')
        ->select('company_wallet_history.*','users.name')
        ->join('users','users.id','=','company_wallet_history.users_id')
        ->when(!empty($search),function($query)use($search){
            $query->where(function($query) use($search){
                $query->orWhere('company_wallet_history.reference','like','%'.$search.'%')
                ->orWhere('company_wallet_history.amount','like','%'.$search.'%')
                ->orWhere('users.name','like','%'.$search.'%');
            });
        })
        ->whereBetween('company_wallet_history.created_at',[($filters['from'] ?? "0000-00-00")." 00:00:00",($filters['to'] ?? date("Y-m-d"))." 23:59:59"])
        ->orderBy('company_wallet_history.created_at','DESC')
        ->paginate($rows ?? 10);

        }
        return view('pages.report.fund-history',compact('query','filters'));
    }

    public function download(Request $request)
    {

        $filters=[
            "search"=>$request->search,
            "from"  =>$request->from,
            "to"    =>$request->to,
            "rows"  =>$request->rows,
        ];
        extract($filters);

        if($rows == "All"){

        $query = DB::table('company_wallet_history')
        ->select('company_wallet_history.reference',
        DB::raw('CASE
            WHEN company_wallet_history.status = "DEBIT" THEN -company_wallet_history.amount
            ELSE +company_wallet_history.amount
        END AS amount'),'users.name','company_wallet_history.created_at')
        ->join('users','users.id','=','company_wallet_history.users_id')
        ->when(!empty($search),function($query)use($search){
            $query->where(function($query) use($search){
                $query->orWhere('company_wallet_history.reference','like','%'.$search.'%')
                ->orWhere('company_wallet_history.amount','like','%'.$search.'%')
                ->orWhere('users.name','like','%'.$search.'%');
            });
        })
        ->whereBetween('company_wallet_history.created_at',[($filters['from'] ?? "0000-00-00")." 00:00:00",($filters['to'] ?? date("Y-m-d"))." 23:59:59"])
        ->orderBy('company_wallet_history.created_at','DESC')
        ->get()
        ->toArray();

        $credit = DB::table('company_wallet_history')
        ->select('company_wallet_history.*','users.name')
        ->join('users','users.id','=','company_wallet_history.users_id')
        ->when(!empty($search),function($query)use($search){
            $query->where(function($query) use($search){
                $query->orWhere('company_wallet_history.reference','like','%'.$search.'%')
                ->orWhere('company_wallet_history.amount','like','%'.$search.'%')
                ->orWhere('users.name','like','%'.$search.'%');
            });
        })
        ->where('company_wallet_history.status','CREDIT')
        ->whereBetween('company_wallet_history.created_at',[($filters['from'] ?? "0000-00-00")." 00:00:00",($filters['to'] ?? date("Y-m-d"))." 23:59:59"])
        ->orderBy('company_wallet_history.created_at','DESC')
        ->sum('company_wallet_history.amount');

        $debit = DB::table('company_wallet_history')
        ->select('company_wallet_history.*','users.name')
        ->join('users','users.id','=','company_wallet_history.users_id')
        ->when(!empty($search),function($query)use($search){
            $query->where(function($query) use($search){
                $query->orWhere('company_wallet_history.reference','like','%'.$search.'%')
                ->orWhere('company_wallet_history.amount','like','%'.$search.'%')
                ->orWhere('users.name','like','%'.$search.'%');
            });
        })
        ->where('company_wallet_history.status','DEBIT')
        ->whereBetween('company_wallet_history.created_at',[($filters['from'] ?? "0000-00-00")." 00:00:00",($filters['to'] ?? date("Y-m-d"))." 23:59:59"])
        ->orderBy('company_wallet_history.created_at','DESC')
        ->sum('company_wallet_history.amount');

        }else{

        $query = DB::table('company_wallet_history')
        ->select('company_wallet_history.reference',
        DB::raw('CASE
            WHEN company_wallet_history.status = "DEBIT" THEN -company_wallet_history.amount
            ELSE +company_wallet_history.amount
        END AS amount'),'users.name','company_wallet_history.created_at')
        ->join('users','users.id','=','company_wallet_history.users_id')
        ->when(!empty($search),function($query)use($search){
            $query->where(function($query) use($search){
                $query->orWhere('company_wallet_history.reference','like','%'.$search.'%')
                ->orWhere('company_wallet_history.amount','like','%'.$search.'%')
                ->orWhere('users.name','like','%'.$search.'%');
            });
        })
        ->whereBetween('company_wallet_history.created_at',[($filters['from'] ?? "0000-00-00")." 00:00:00",($filters['to'] ?? date("Y-m-d"))." 23:59:59"])
        ->orderBy('company_wallet_history.created_at','DESC')
        ->limit($rows ?? 10)
        ->get()
        ->toArray();

        $credit = DB::table('company_wallet_history')
        ->select('company_wallet_history.*','users.name')
        ->join('users','users.id','=','company_wallet_history.users_id')
        ->when(!empty($search),function($query)use($search){
            $query->where(function($query) use($search){
                $query->orWhere('company_wallet_history.reference','like','%'.$search.'%')
                ->orWhere('company_wallet_history.amount','like','%'.$search.'%')
                ->orWhere('users.name','like','%'.$search.'%');
            });
        })
        ->where('company_wallet_history.status','CREDIT')
        ->whereBetween('company_wallet_history.created_at',[($filters['from'] ?? "0000-00-00")." 00:00:00",($filters['to'] ?? date("Y-m-d"))." 23:59:59"])
        ->orderBy('company_wallet_history.created_at','DESC')
        ->limit($rows ?? 10)
        ->sum('company_wallet_history.amount');

        $debit = DB::table('company_wallet_history')
        ->select('company_wallet_history.*','users.name')
        ->join('users','users.id','=','company_wallet_history.users_id')
        ->when(!empty($search),function($query)use($search){
            $query->where(function($query) use($search){
                $query->orWhere('company_wallet_history.reference','like','%'.$search.'%')
                ->orWhere('company_wallet_history.amount','like','%'.$search.'%')
                ->orWhere('users.name','like','%'.$search.'%');
            });
        })
        ->where('company_wallet_history.status','DEBIT')
        ->whereBetween('company_wallet_history.created_at',[($filters['from'] ?? "0000-00-00")." 00:00:00",($filters['to'] ?? date("Y-m-d"))." 23:59:59"])
        ->orderBy('company_wallet_history.created_at','DESC')
        ->limit($rows ?? 10)
        ->sum('company_wallet_history.amount');

        }

        $new_row = (object)[
            'Reference'       => 'Total Credit Amount',
            'Amount(₱)'       => '₱'.number_format($credit,2),
            'Processed By'    => 'Total Debit Amount',
            'Processed Date'  => '₱'.number_format($debit,2),
        ];
        $query[]      = $new_row;
        $excel_data   = [];
        $excel_header = ['Reference','Amount(₱)','Processed By','Processed Date'];
        $excel_data=array_merge($excel_data,$query);
        return Excel::download(new ExportExcel($excel_header,$excel_data), 'fund-history-'.date('Y-m-d').'.xlsx');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
