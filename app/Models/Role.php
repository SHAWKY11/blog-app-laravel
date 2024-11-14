<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Laratrust\Models\Role as RoleModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Role extends RoleModel
{
    use HasFactory;

       public function user()
    {
        return $this->belongsTo(User::class);
    }
}
