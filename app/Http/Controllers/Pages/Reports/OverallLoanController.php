<?php

namespace App\Http\Controllers\Pages\Reports;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Exports\ExportExcel;
use Maatwebsite\Excel\Facades\Excel;
class OverallLoanController extends Controller
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

        $query = DB::table('payment_account')
        ->where('status',2)
        ->when(!empty($search),function($query)use($search){
            $query->where(function($query) use($search){
                $query->orWhere('reference','like','%'.$search.'%')
                ->orWhere('amount','like','%'.$search.'%');
            });
        })
        ->whereBetween('disbursement_date',[($filters['from'] ?? "0000-00-00")." 00:00:00",($filters['to'] ?? date("Y-m-d"))." 23:59:59"])
        ->orderBy('disbursement_date','DESC')
        ->get();

        }else{

        $query = DB::table('payment_account')
        ->where('status',2)
        ->when(!empty($search),function($query)use($search){
            $query->where(function($query) use($search){
                $query->orWhere('reference','like','%'.$search.'%')
                ->orWhere('amount','like','%'.$search.'%');
            });
        })
        ->whereBetween('disbursement_date',[($filters['from'] ?? "0000-00-00")." 00:00:00",($filters['to'] ?? date("Y-m-d"))." 23:59:59"])
        ->orderBy('disbursement_date','DESC')
        ->paginate($rows ?? 10);

        }
        return view('pages.report.overall-loan',compact('query','filters'));
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

        $query = DB::table('payment_account')
        ->select('reference','amount','disbursement_date')
        ->where('status',2)
        ->when(!empty($search),function($query)use($search){
            $query->where(function($query) use($search){
                $query->orWhere('reference','like','%'.$search.'%')
                ->orWhere('amount','like','%'.$search.'%');
            });
        })
        ->whereBetween('disbursement_date',[($filters['from'] ?? "0000-00-00")." 00:00:00",($filters['to'] ?? date("Y-m-d"))." 23:59:59"])
        ->orderBy('disbursement_date','DESC')
        ->get()
        ->toArray();

        }else{

        $query = DB::table('payment_account')
        ->select('reference','amount','disbursement_date')
        ->where('status',2)
        ->when(!empty($search),function($query)use($search){
            $query->where(function($query) use($search){
                $query->orWhere('reference','like','%'.$search.'%')
                ->orWhere('amount','like','%'.$search.'%');
            });
        })
        ->whereBetween('disbursement_date',[($filters['from'] ?? "0000-00-00")." 00:00:00",($filters['to'] ?? date("Y-m-d"))." 23:59:59"])
        ->orderBy('disbursement_date','DESC')
        ->limit($rows ?? 10)
        ->get()
        ->toArray();
        }

        $total_amount = 0;
        foreach($query as $sum){
            $total_amount += $sum->amount;
        }
        $new_row = (object)[
            'Reference' => 'Total Amount',
            'Amount(₱)' => '₱'.number_format($total_amount,2),
            'Date'      => '',
        ];
        $query[]      = $new_row;
        $excel_data   = [];
        $excel_header = ['Reference','Amount(₱)','Date'];
        $excel_data=array_merge($excel_data,$query);
        return Excel::download(new ExportExcel($excel_header,$excel_data), 'overall-loan-'.date('Y-m-d').'.xlsx');
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
