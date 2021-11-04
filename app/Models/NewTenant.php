<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;
use DB;

class NewTenant extends Model
{
    use HasFactory;

    protected $connection = 'landlord';
    protected $table = 'tenants';
    protected $fillable = [
        'name', 'domain', 'database',
    ];

    public function configure()
    {
        $query = DB::connection('tenant')->select("SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = '{$this->database}'");

        if(!$query){
            DB::connection('tenant')->statement("CREATE DATABASE {$this->database}");
        }
        
        config([
            'database.connections.tenant.database' => $this->database,
        ]);
        
        DB::purge('tenant');

        DB::reconnect('tenant');

        Schema::connection('tenant')->getConnection()->reconnect();

        return $this;
    }

    public function use()
    {
        app()->forgetInstance('tenant');

        app()->instance('tenant', $this);

        return $this;
    }
}
