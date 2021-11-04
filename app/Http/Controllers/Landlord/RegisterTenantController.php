<?php

namespace App\Http\Controllers\Landlord;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Artisan;
use App\Models\NewTenant;
use DB;

class RegisterTenantController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('landlord.tenant-signup');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:landlord.tenants',
            'domain' => 'required|unique:landlord.tenants',
            'database' => 'required|unique:landlord.tenants',
        ]);

        NewTenant::create([
            'name' => $request->name,
            'domain' => strtolower($request->domain).'.localhost',
            'database' => 'tenant_'.strtolower($request->database),
        ]);
        // migrate new tenant
        Artisan::call('new-tenant:migrate');
        return redirect()->route('client.dashboard')->withStatus('Created Successfully');
        // return redirect()->to('http://'.$request->domain.'.localhost:8000');
    }

    public function edit(Request $request)
    {
        $tenant = NewTenant::findOrFail($request->tntId);

        return response()->json([
            'name' => $tenant->name,
            'domain' => $tenant->domain,
            'database' => $tenant->database,
            'id' => $tenant->id,
        ]);
    }

    public function update(Request $request, $id)
    {
        $tenant = NewTenant::where('id',$id)
                            ->update(['name' => $request->name]);

        return redirect()->route('client.dashboard')->withStatus('Updated Successfully');       
    }

    public function deleteTenant($id)
    {
        $tenant = NewTenant::findOrFail($id);

        $q = DB::connection('tenant')
            ->select("SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = '{$tenant->database}'");

        if($q){
            DB::connection('tenant')->statement("DROP DATABASE {$tenant->database}");
        }
        $tenant->delete();

        return redirect()->route('client.dashboard')->withStatus('Deleted Successfully');
    }
}
