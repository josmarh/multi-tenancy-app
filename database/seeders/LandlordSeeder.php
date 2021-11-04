<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\NewTenant;

class LandlordSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        NewTenant::create([
            'name' => 'shop',
            'domain' => 'shop.localhost',
            'database' => 'tenant_shop',
        ]);

        NewTenant::create([
            'name' => 'blog',
            'domain' => 'blog.localhost',
            'database' => 'tenant_blog',
        ]);
    }
}
