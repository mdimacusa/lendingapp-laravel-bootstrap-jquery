<?php

namespace App\Http\Controllers\Pages\Settings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use DB;
use Crypt;
use Http;
class RolesAndPermissionsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if (!auth()->user()->can('access','roles-and-permissions')) {
                abort(403,'Unauthorized action.');
            }

            return $next($request);
        });
    }

    public function index()
    {
        $query  = DB::table('roles')->get();

        return view('pages.settings.roles-and-permissions.index',compact('query'));
    }

    public function create_permission()
    {
        return view('pages.settings.roles-and-permissions.create-permission');
    }
    public function store_permission(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'    => 'required',
            'module'  => 'required',
            'slug'    => 'required',
        ]);

        if($validator->fails()){
            return back()->with("errors",$validator->errors())->withErrors($validator->errors())->withInput();
        }

        DB::table('permissions')->insert([
            'name'   => $request->name,
            'module' => $request->module,
            'slug'   => $request->slug
        ]);
        return redirect(route('settings.roles-and-permissions.create-permission'))->with("swal.success","Permission Created");
    }

    public function create_role()
    {
        $permissions = DB::table('permissions')
                       ->select(DB::raw('DISTINCT module'))->get();

        foreach($permissions as $key => $module){
            $permissions[$key]->permission = DB::table('permissions')
                                            ->select('permissions.*',DB::raw('0 as status'))
                                            ->where('module',$module->module)
                                            ->orderBy('permissions.id','asc')
                                            ->get();
        }
        return view('pages.settings.roles-and-permissions.create-role',compact('permissions'));
    }

    public function store_role(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'    => 'required',
        ]);

        if($validator->fails()){
            return back()->with("errors",$validator->errors())->withErrors($validator->errors())->withInput();
        }

        $role_id  = DB::table('roles')->insertGetId(['title'=>$request->name]);

        $permissions_ids  = array_keys($request->permissions ?? []);
        $role             = DB::table('roles')->where('id',$role_id)->first();

        foreach($request->permissions ?? [] as $permission_id => $permission_status){
            $condition = DB::table('role_permissions')
                        ->where(["roles_id" => $role->id,"permissions_id" => $permission_id])
                        ->exists();

            if(!$condition)
            DB::table('role_permissions')->insert([
                "roles_id"        => $role->id,
                "permissions_id"  => $permission_id
            ]);
        }
        return redirect(route('settings.roles-and-permissions.create-role'))->with("swal.success","Role Created");
    }

    public function edit($role_id)
    {
        if (!auth()->user()->can('update','roles-and-permissions')) {
            abort(403, 'Unauthorized action.');
        }
        $role_id      = Crypt::decrypt($role_id);
        $role         = DB::table('roles')->where('id',$role_id)
                        ->first();
        $permissions  = DB::table('permissions')
                        ->select(DB::raw('DISTINCT module'))
                        ->get();
        foreach($permissions as $key => $module){

            $permissions[$key]->permission = DB::table('permissions')
                                            ->select('permissions.*',DB::raw('role_permissions.id is not null as status'))
                                            ->leftJoin('role_permissions',function($join) use($role){
                                                $join->on('role_permissions.permissions_id','=','permissions.id')
                                                ->where('role_permissions.roles_id',$role->id);
                                            })
                                            ->where('module',$module->module)
                                            ->orderBy('permissions.id','asc')
                                            ->get();
        }
        return view('pages.settings.roles-and-permissions.edit-permissions',compact('permissions','role'));
    }
    public function update(Request $request,$role_id)
    {

        if (!auth()->user()->can('update','roles-and-permissions')) {
            abort(403, 'Unauthorized action.');
        }
        $permissions_ids    = array_keys($request->permissions ?? []);
        $role_id            = Crypt::decrypt($role_id);
        $role               = DB::table('roles')->where('id',$role_id)
                              ->first();

        DB::table('role_permissions')
        ->where(["roles_id" => $role->id])
        ->whereNotIn('permissions_id',$permissions_ids)
        ->delete();
        foreach($request->permissions ?? [] as $permission_id => $permission_status){

            $permission_query = DB::table('permissions')
                                ->where('id',$permission_id)
                                ->first();

            $condition = DB::table('role_permissions')
                        ->where(["roles_id" => $role->id,"permissions_id" => $permission_id])
                        ->exists();

            if(!$condition)
            DB::table('role_permissions')->insert([
                "roles_id"        => $role->id,
                "permissions_id"  => $permission_query->id
            ]);
        }
        return redirect(route('settings.roles-and-permissions.edit',['role_id'=>Crypt::encrypt($role_id)]))->with("swal.success","Role Permissions Updated");
    }
}
