<?php

namespace App\Http\Controllers\Pages\UserManagement;
use App\Http\Controllers\Controller;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use DB;
use Crypt;
use Hash;
class AdministratorController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if (!auth()->user()->can('access','administrator')) {
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
        $users = DB::table('users')
        ->where('role',"ADMINISTRATOR")
        ->when(!empty($search),function($query)use($search){
            $query->where(function($query) use($search){
                $query->orWhere('unique_id','like','%'.$search.'%')
                ->orWhere('name','like','%'.$search.'%')
                ->orWhere('email','like','%'.$search.'%');
            });
        })
        ->whereBetween('created_at',[($filters['from'] ?? "0000-00-00")." 00:00:00",($filters['to'] ?? date("Y-m-d"))." 23:59:59"])
        ->get();
        }else{
        $users = DB::table('users')
        ->where('role',"ADMINISTRATOR")
        ->when(!empty($search),function($query)use($search){
            $query->where(function($query) use($search){
                $query->orWhere('unique_id','like','%'.$search.'%')
                ->orWhere('name','like','%'.$search.'%')
                ->orWhere('email','like','%'.$search.'%');
            });
        })
        ->whereBetween('created_at',[($filters['from'] ?? "0000-00-00")." 00:00:00",($filters['to'] ?? date("Y-m-d"))." 23:59:59"])
        ->paginate($rows ?? 10);
        }
        return view('pages.user-management.administrator.index',compact('users','filters'));
    }

    public function create()
    {
        if (!auth()->user()->can('create','administrator')) {
            abort(403, 'Unauthorized action.');
        }
        return view('pages.user-management.administrator.create');
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!auth()->user()->can('create','administrator')) {
            abort(403, 'Unauthorized action.');
        }

        DB::beginTransaction();
        try {

            $validator = Validator::make($request->all(), [
                'first_name'            => 'required',
                'surname'               => 'required',
                'email'                 => 'required|email|unique:users,email',
                'pincode'               => 'required|numeric|digits:4',
                'password'              => 'required|confirmed',
                'password_confirmation' => 'required',
            ]);

            if($validator->fails()){
                return back()->with("errors",$validator->errors())->withErrors($validator->errors())->withInput();
            }

            if(auth()->user()->pincode != $request->pincode){
                return back()->withErrors(['pincode'=>'The pincode is invalid']);
            }

            $uniqueid = rand(11111111,99999999);
            $name     = preg_replace('/\s+/', ' ',$request->first_name.' '.$request->middle_name.' '.$request->surname);
            $email    = $request->email;
            $pincode  = $request->pincode;
            $password = Hash::make($request->password);

            $query = DB::table('users')->insert([
                'unique_id'     => $uniqueid,
                'name'          => $name,
                'email'         => $email,
                'pincode'       => $pincode,
                'password'      => $password,
                'role'          => "ADMINISTRATOR",
                'status'        => "ACTIVE",
            ]);

            DB::commit();
            if($query) {
                return back()->with("swal.success","Successfully Administrator Added");
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
        $users = DB::table('users')->where('id',$id)->first();

        if($rows == "All"){

            $query = DB::table('soa')
            ->where(['users_id'=>$id])
            ->when(!empty($search),function($query)use($search){
                $query->where(function($query) use($search){
                    $query->orWhere('reference','like','%'.$search.'%')
                    ->orWhere('amount','like','%'.$search.'%');
                });
            })
            ->whereBetween('disbursement_date',[($filters['from'] ?? "0000-00-00")." 00:00:00",($filters['to'] ?? date("Y-m-d"))." 23:59:59"])
            ->orderBy('disbursement_date','DESC')
            ->get();

            $administrator_fully_paid     = DB::table('soa')->where(['users_id'=>$id,'status'=>2])->sum('amount');
            $administrator_partially_paid = DB::table('soa')->where(['users_id'=>$id,'status'=>1])->sum('amount');
            $administrator_unpaid         = DB::table('soa')->where(['users_id'=>$id,'status'=>0])->sum('amount');
            $total_deposit                = DB::table('company_wallet_history')->where(['users_id'=>$id,'status'=>'CREDIT'])->sum('amount');

            }else{

                if($tab=="deposit")
                {
                    $query = DB::table('company_wallet_history')
                    ->where(['users_id'=>$id])
                    ->when(!empty($search),function($query)use($search){
                        $query->where(function($query) use($search){
                            $query->orWhere('reference','like','%'.$search.'%')
                            ->orWhere('amount','like','%'.$search.'%');
                        });
                    })
                    ->whereBetween('created_at',[($filters['from'] ?? "0000-00-00")." 00:00:00",($filters['to'] ?? date("Y-m-d"))." 23:59:59"])
                    ->orderBy('created_at','DESC')
                    ->paginate($rows ?? 10);
                }
                else
                {
                    $query = DB::table('soa')
                    ->where(['users_id'=>$id])
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


            $administrator_fully_paid = DB::table('soa')
            ->where(['users_id'=>$id,'status'=>2])
            ->when(!empty($search),function($query)use($search){
                $query->where(function($query) use($search){
                    $query->orWhere('reference','like','%'.$search.'%')
                    ->orWhere('amount','like','%'.$search.'%');
                });
            })
            ->whereBetween('disbursement_date',[($filters['from'] ?? "0000-00-00")." 00:00:00",($filters['to'] ?? date("Y-m-d"))." 23:59:59"])
            ->sum('amount');

            $administrator_partially_paid = DB::table('soa')
            ->where(['users_id'=>$id,'status'=>1])
            ->when(!empty($search),function($query)use($search){
                $query->where(function($query) use($search){
                    $query->orWhere('reference','like','%'.$search.'%')
                    ->orWhere('amount','like','%'.$search.'%');
                });
            })
            ->whereBetween('disbursement_date',[($filters['from'] ?? "0000-00-00")." 00:00:00",($filters['to'] ?? date("Y-m-d"))." 23:59:59"])
            ->sum('amount');

            $administrator_unpaid = DB::table('soa')
            ->where(['users_id'=>$id,'status'=>0])
            ->when(!empty($search),function($query)use($search){
                $query->where(function($query) use($search){
                    $query->orWhere('reference','like','%'.$search.'%')
                    ->orWhere('amount','like','%'.$search.'%');
                });
            })
            ->whereBetween('disbursement_date',[($filters['from'] ?? "0000-00-00")." 00:00:00",($filters['to'] ?? date("Y-m-d"))." 23:59:59"])
            ->sum('amount');

            $total_deposit = DB::table('company_wallet_history')
            ->where(['users_id'=>$id,'status'=>'CREDIT'])
            ->when(!empty($search),function($query)use($search){
                $query->where(function($query) use($search){
                    $query->orWhere('reference','like','%'.$search.'%')
                    ->orWhere('amount','like','%'.$search.'%');
                });
            })
            ->whereBetween('created_at',[($filters['from'] ?? "0000-00-00")." 00:00:00",($filters['to'] ?? date("Y-m-d"))." 23:59:59"])
            ->sum('amount');
            }
        return view('pages.user-management.administrator.index-profile',compact('users','query','tab','filters','administrator_fully_paid','administrator_partially_paid','administrator_unpaid','total_deposit'));
    }

    public function update(Request $request,$tab,$id)
    {
        if (!auth()->user()->can('update','administrator')) {
            abort(403, 'Unauthorized action.');
        }
        DB::beginTransaction();
        try {
            $id = Crypt::decrypt($id);

            $validator = Validator::make($request->all(), [
                'full_name'             => 'required',
                'email'                 => 'required|email|unique:users,email,'.$id,
                'pincode'               => 'required|numeric|digits:4',
                'password'              => 'required|confirmed',
                'password_confirmation' => 'required',

            ]);

            if($validator->fails()){
                return back()->with("errors",$validator->errors())->withErrors($validator->errors())->withInput();
            }

            DB::table('users')
            ->where('id',$id)
            ->update([
                'name'          => $request->full_name,
                'email'         => $request->email,
                'pincode'       => $request->pincode,
                'password'      => Hash::make($request->password),
                'status'        => $request->status,
            ]);
            DB::commit();
            return redirect(route('user-management.administrator.profile',['tab'=>'summary','id'=>Crypt::encrypt($id)]))->with("swal.success","Successfully Administrator Updated");


        } catch(Throwable $exception) {
            DB::rollBack();
            return back()->with("swal.error",$exception->getMessage())->withInput();
        }
    }

    public function seen_notification($id)
    {
        // $id = Crypt::decrypt($id);
        $query = DB::table('notifications')->where('id',$id)
        ->update([
            '_seen' => 'Yes',
        ]);
        // return response()->json(['status' => 'success']);
        return redirect()->back();
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
