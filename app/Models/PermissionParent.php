<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Permission;

class PermissionParent extends Model
{
    protected $table    = 'permission_parents';
    protected $fillable = ['parent_name'];

    public function permissions()
    {
        return $this->hasMany(Permission::class, 'permission_parent_id', 'id');
    }
}
