<?php

namespace App\Http\Controllers\Pages\UserManagement;
use App\Http\Controllers\Controller;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
class ClientController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if (!auth()->user()->can('access','client')) {
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
        $clients = DB::table('client')
        ->when(!empty($search),function($query)use($search){
            $query->where(function($query) use($search){
                $query->orWhere('unique_id','like','%'.$search.'%')
                ->orWhere('first_name','like','%'.$search.'%')
                ->orWhere('middle_name','like','%'.$search.'%')
                ->orWhere('surname','like','%'.$search.'%')
                ->orWhere('email','like','%'.$search.'%')
                ->orWhere('contact_number','like','%'.$search.'%');
            });
        })
        ->whereBetween('created_at',[($filters['from'] ?? "0000-00-00")." 00:00:00",($filters['to'] ?? date("Y-m-d"))." 23:59:59"])
        ->get();
        }else{
        $clients = DB::table('client')
        ->when(!empty($search),function($query)use($search){
            $query->where(function($query) use($search){
                $query->orWhere('unique_id','like','%'.$search.'%')
                ->orWhere('first_name','like','%'.$search.'%')
                ->orWhere('middle_name','like','%'.$search.'%')
                ->orWhere('surname','like','%'.$search.'%')
                ->orWhere('email','like','%'.$search.'%')
                ->orWhere('contact_number','like','%'.$search.'%');
            });
        })
        ->whereBetween('created_at',[($filters['from'] ?? "0000-00-00")." 00:00:00",($filters['to'] ?? date("Y-m-d"))." 23:59:59"])
        ->paginate($rows ?? 10);
        }
        return view('pages.user-management.client',compact('clients','filters'));
    }

    public function create()
    {
        if (!auth()->user()->can('create','client')) {
            abort(403, 'Unauthorized action.');
        }
        return view('pages.user-management.create');
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!auth()->user()->can('create','client')) {
            abort(403, 'Unauthorized action.');
        }
        DB::beginTransaction();
        try {

            $validator = Validator::make($request->all(), [
                'first_name'     => 'required',
                'surname'        => 'required',
                'email'          => 'required|email|unique:client,email',
                'contact'        => 'required|unique:client,contact_number',
            ]);

            if($validator->fails()){
                return back()->with("errors",$validator->errors())->withErrors($validator->errors())->withInput();
            }

            $uniqueid = rand(11111111,99999999);
            $fname    = $request->first_name;
            $mname    = $request->middle_name;
            $surname  = $request->surname;
            $email    = $request->email;
            $contact  = $request->contact;
            $address  = $request->address;

            $query = DB::table('client')->insert([
                'unique_id'     => $uniqueid,
                'first_name'    => $fname,
                'middle_name'   => $mname,
                'surname'       => $surname,
                'email'         => $email,
                'contact_number'=> $contact,
                'address'       => $address,
                'status'        => "ACTIVE",
            ]);

            DB::commit();
            if($query) {
                return back()->with("swal.success","Successfully Client Added");
            }

        } catch(Throwable $exception) {
            DB::rollBack();
            return back()->with("swal.error",$exception->getMessage())->withInput();
        }
    }

    public function index_profile(Request $request,$tab,$id)
    {
        $filters=[
            "search"=>$request->search,
            "from"  =>$request->from,
            "to"    =>$request->to,
            "rows"  =>$request->rows,
        ];
        extract($filters);
        $id = Crypt::decrypt($id);
        $clients = DB::table('client')->where('id',$id)->first();

        if($rows == "All"){

            $query = DB::table('soa')
            ->where(['client_id'=>$id])
            ->when(!empty($search),function($query)use($search){
                $query->where(function($query) use($search){
                    $query->orWhere('reference','like','%'.$search.'%')
                    ->orWhere('amount','like','%'.$search.'%');
                });
            })
            ->whereBetween('disbursement_date',[($filters['from'] ?? "0000-00-00")." 00:00:00",($filters['to'] ?? date("Y-m-d"))." 23:59:59"])
            ->orderBy('disbursement_date','DESC')
            ->get();

            $client_fully_paid     = DB::table('soa')->where(['client_id'=>$id,'status'=>2])->sum('amount');
            $client_partially_paid = DB::table('soa')->where(['client_id'=>$id,'status'=>1])->sum('amount');
            $client_unpaid         = DB::table('soa')->where(['client_id'=>$id,'status'=>0])->sum('amount');


            }else{

            $query = DB::table('soa')
            ->where(['client_id'=>$id])
            ->when(!empty($search),function($query)use($search){
                $query->where(function($query) use($search){
                    $query->orWhere('reference','like','%'.$search.'%')
                    ->orWhere('amount','like','%'.$search.'%');
                });
            })
            ->whereBetween('disbursement_date',[($filters['from'] ?? "0000-00-00")." 00:00:00",($filters['to'] ?? date("Y-m-d"))." 23:59:59"])
            ->orderBy('disbursement_date','DESC')
            ->paginate($rows ?? 10);

            $client_fully_paid = DB::table('soa')
            ->where(['client_id'=>$id,'status'=>2])
            ->when(!empty($search),function($query)use($search){
                $query->where(function($query) use($search){
                    $query->orWhere('reference','like','%'.$search.'%')
                    ->orWhere('amount','like','%'.$search.'%');
                });
            })
            ->whereBetween('disbursement_date',[($filters['from'] ?? "0000-00-00")." 00:00:00",($filters['to'] ?? date("Y-m-d"))." 23:59:59"])
            ->sum('amount');

            $client_partially_paid = DB::table('soa')
            ->where(['client_id'=>$id,'status'=>1])
            ->when(!empty($search),function($query)use($search){
                $query->where(function($query) use($search){
                    $query->orWhere('reference','like','%'.$search.'%')
                    ->orWhere('amount','like','%'.$search.'%');
                });
            })
            ->whereBetween('disbursement_date',[($filters['from'] ?? "0000-00-00")." 00:00:00",($filters['to'] ?? date("Y-m-d"))." 23:59:59"])
            ->sum('amount');

            $client_unpaid = DB::table('soa')
            ->where(['client_id'=>$id,'status'=>0])
            ->when(!empty($search),function($query)use($search){
                $query->where(function($query) use($search){
                    $query->orWhere('reference','like','%'.$search.'%')
                    ->orWhere('amount','like','%'.$search.'%');
                });
            })
            ->whereBetween('disbursement_date',[($filters['from'] ?? "0000-00-00")." 00:00:00",($filters['to'] ?? date("Y-m-d"))." 23:59:59"])
            ->sum('amount');
            }
        return view('pages.user-management.index-profile',compact('clients','query','tab','filters','client_fully_paid','client_partially_paid','client_unpaid'));
    }

    public function update(Request $request,$tab,$id)
    {

        if (!auth()->user()->can('update','client')) {
            abort(403, 'Unauthorized action.');
        }
        DB::beginTransaction();
        try {
            $id = Crypt::decrypt($id);

            $validator = Validator::make($request->all(), [
                'first_name'     => 'required',
                'surname'        => 'required',
                'email'          => 'required|email|unique:client,email,'.$id,
                'contact'        => 'required|unique:client,contact_number,'.$id,
            ]);

            if($validator->fails()){
                return back()->with("errors",$validator->errors())->withErrors($validator->errors())->withInput();
            }

            DB::table('client')
            ->where('id',$id)
            ->update([
                'first_name'    => $request->first_name,
                'middle_name'   => $request->middle_name,
                'surname'       => $request->surname,
                'email'         => $request->email,
                'contact_number'=> $request->contact,
                'address'       => $request->address,
                'status'        => $request->status,
            ]);

            DB::commit();
            return redirect(route('user-management.client.profile',['tab'=>'summary','id'=>Crypt::encrypt($id)]))->with("swal.success","Successfully Client Updated");


        } catch(Throwable $exception) {
            DB::rollBack();
            return back()->with("swal.error",$exception->getMessage())->withInput();
        }
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
