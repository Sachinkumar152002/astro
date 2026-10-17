<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Notifications\AdminResetPasswordNotification;
use App\Model\Permission;

class Admin extends Authenticatable
{
    use Notifiable;

    protected $guard = 'admin';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'job_title',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new AdminResetPasswordNotification($token));
    }

    public function role()
    {
        return $this->hasOne('App\Model\Role','id','role_id');
    }

    public function can($module = '', $check = '') {

        $role_id = $this->role_id;
        if($this->id==1 || $role_id==1){
          return true;
        }
        $permisssion = null;
        if($this->status){
          $permisssion = Permission::where('role_id', $role_id)
          ->whereHas('module', function($query) use($module){
            $query->where('key', $module);
          })->first();
        }

        $allow = false;

        if($permisssion) {
            switch ($check) {
                case 'add':
                    $allow = $permisssion->can_add;
                    break;
                case 'view':
                    $allow = $permisssion->can_view;
                    break;
                case 'delete':
                    $allow = $permisssion->can_delete;
                    break;
                case 'update':
                    $allow = $permisssion->can_update;
                    break;
                case 'any':
                    $allow = $permisssion->can_add || $permisssion->can_view || $permisssion->can_update || $permisssion->can_delete;
                    break;
            }
        }
        return $allow;
    }

}
