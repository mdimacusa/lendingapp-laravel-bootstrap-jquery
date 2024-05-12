<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use DB;
class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        Gate::define('access',function($user,$module){
            $permission_query   = DB::table('permissions')->where('slug','access.'.$module)->first();
            $role_query         = DB::table('roles')->where('title',$user->role)->first();
            return $user->role == 'SUPER ADMINISTRATOR' || (!empty($permission_query)&&!empty($role_query))&&(DB::table("role_permissions")->where(['roles_id' => $role_query->id,'permissions_id' => $permission_query->id]))->exists();
        });

        Gate::define('read-write',function($user,$module){
            $permission_query   = DB::table('permissions')->where('slug','read-write.'.$module)->first();
            $role_query         = DB::table('roles')->where('title',$user->role)->first();
            return $user->role == 'SUPER ADMINISTRATOR' || (!empty($permission_query)&&!empty($role_query))&&(DB::table("role_permissions")->where(['roles_id' => $role_query->id,'permissions_id' => $permission_query->id]))->exists();
        });
        Gate::define('create',function($user,$module){
            $permission_query   = DB::table('permissions')->where('slug','create.'.$module)->first();
            $role_query         = DB::table('roles')->where('title',$user->role)->first();
            return $user->role == 'SUPER ADMINISTRATOR' || (!empty($permission_query)&&!empty($role_query))&&(DB::table("role_permissions")->where(['roles_id' => $role_query->id,'permissions_id' => $permission_query->id]))->exists();
        });
        Gate::define('update',function($user,$module){
            $permission_query   = DB::table('permissions')->where('slug','update.'.$module)->first();
            $role_query         = DB::table('roles')->where('title',$user->role)->first();
            return $user->role == 'SUPER ADMINISTRATOR' || (!empty($permission_query)&&!empty($role_query))&&(DB::table("role_permissions")->where(['roles_id' => $role_query->id,'permissions_id' => $permission_query->id]))->exists();
        });
        Gate::define('delete',function($user,$module){
            $permission_query   = DB::table('permissions')->where('slug','delete.'.$module)->first();
            $role_query         = DB::table('roles')->where('title',$user->role)->first();
            return $user->role == 'SUPER ADMINISTRATOR' || (!empty($permission_query)&&!empty($role_query))&&(DB::table("role_permissions")->where(['roles_id' => $role_query->id,'permissions_id' => $permission_query->id]))->exists();
        });
        Gate::define('do',function($user,$action,$module){
            $permission_query   = DB::table('permissions')->where('slug',$action.'.'.$module)->first();
            $role_query         = DB::table('roles')->where('title',$user->role)->first();
            return $user->role == 'SUPER ADMINISTRATOR' || (!empty($permission_query)&&!empty($role_query))&&(DB::table("role_permissions")->where(['roles_id' => $role_query->id,'permissions_id' => $permission_query->id]))->exists();

        });
    }
}
