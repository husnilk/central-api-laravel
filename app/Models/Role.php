<?php

namespace App\Models;

use Spatie\Permission\Models\Role as SpatieRole;

class Role extends SpatieRole
{
    protected $hidden = ["id", "created_at", "updated_at", "pivot"];
}
