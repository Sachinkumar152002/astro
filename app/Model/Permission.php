<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{

    public function role()
    {
    return $this->hasOne('App\Model\Role', 'id', 'role_id');
    }

    public function module()
    {
    return $this->hasOne('App\Model\Module', 'id', 'module_id');
    }
    public function permission($parent_id, $module_id){
      return Permission::where('parent_id', $parent_id)->where('module_id', $module_id)->first();
    }
}
