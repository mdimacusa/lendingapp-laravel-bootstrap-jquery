<?php

namespace App\Http\Controllers\Pages\Reports;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Exports\ExportExcel;
use Maatwebsite\Excel\Facades\Excel;
class TopBorrowerController extends Controller
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
        $query =  DB::table('soa')
        ->select('client.unique_id','soa.fullname',DB::raw('COALESCE(SUM(soa.amount),0) as borrowed_amount'))
        ->leftJoin('client',function($join){
            $join->on('soa.client_id','=','client.id');
        })
        ->when(!empty($search),function($query)use($search){
            $query->where(function($query) use($search){
                $query->orWhere('client.unique_id','like','%'.$search.'%')
                ->orWhere('soa.fullname','like','%'.$search.'%');
            });
        })
        ->whereBetween('created_at',[($filters['from'] ?? "0000-00-00")." 00:00:00",($filters['to'] ?? date("Y-m-d"))." 23:59:59"])
        ->groupBy('client.unique_id','soa.fullname')
        ->orderByDesc('borrowed_amount')
        ->get();
        }else{
        $query =  DB::table('soa')
        ->select('client.unique_id','soa.fullname',DB::raw('COALESCE(SUM(soa.amount),0) as borrowed_amount'))
        ->leftJoin('client',function($join){
            $join->on('soa.client_id','=','client.id');
        })
        ->when(!empty($search),function($query)use($search){
            $query->where(function($query) use($search){
                $query->orWhere('client.unique_id','like','%'.$search.'%')
                ->orWhere('soa.fullname','like','%'.$search.'%');
            });
        })
        ->whereBetween('created_at',[($filters['from'] ?? "0000-00-00")." 00:00:00",($filters['to'] ?? date("Y-m-d"))." 23:59:59"])
        ->groupBy('client.unique_id','soa.fullname')
        ->orderByDesc('borrowed_amount')
        ->paginate($rows ?? 10);
        }
        return view('pages.report.top-borrower',compact('query','filters'));
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
        $query =  DB::table('soa')
        ->select('client.unique_id','soa.fullname',DB::raw('COALESCE(SUM(soa.amount),0) as borrowed_amount'))
        ->leftJoin('client',function($join){
            $join->on('soa.client_id','=','client.id');
        })
        ->when(!empty($search),function($query)use($search){
            $query->where(function($query) use($search){
                $query->orWhere('client.unique_id','like','%'.$search.'%')
                ->orWhere('soa.fullname','like','%'.$search.'%');
            });
        })
        ->whereBetween('created_at',[($filters['from'] ?? "0000-00-00")." 00:00:00",($filters['to'] ?? date("Y-m-d"))." 23:59:59"])
        ->groupBy('client.unique_id','soa.fullname')
        ->orderByDesc('borrowed_amount')
        ->get()
        ->toArray();
        }else{
        $query =  DB::table('soa')
        ->select('client.unique_id','soa.fullname',DB::raw('COALESCE(SUM(soa.amount),0) as borrowed_amount'))
        ->leftJoin('client',function($join){
            $join->on('soa.client_id','=','client.id');
        })
        ->when(!empty($search),function($query)use($search){
            $query->where(function($query) use($search){
                $query->orWhere('client.unique_id','like','%'.$search.'%')
                ->orWhere('soa.fullname','like','%'.$search.'%');
            });
        })
        ->whereBetween('created_at',[($filters['from'] ?? "0000-00-00")." 00:00:00",($filters['to'] ?? date("Y-m-d"))." 23:59:59"])
        ->groupBy('client.unique_id','soa.fullname')
        ->orderByDesc('borrowed_amount')
        ->limit($rows ?? 10)
        ->get()
        ->toArray();
        }
        $excel_data   = [];
        $excel_header = ['Client Unique ID','Fullname','Total Amount(₱)'];
        $excel_data=array_merge($excel_data,$query);
        return Excel::download(new ExportExcel($excel_header,$excel_data), 'top- borrower-'.date('Y-m-d').'.xlsx');;
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
