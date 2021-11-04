<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Multitenancy\Models\Tenant;
use App\Models\NewTenants;
use Illuminate\Support\Facades\Schema;
use DB;
use Log;


class CustomTenantModel extends Tenant
{
    public $table = 'tenants';

    public static function booted()
    {
        $query=static::creating(fn(CustomTenantModel $model) => $model->createDatabase()); 
    }

    public function createDatabase()
    {
        //
    }
}
