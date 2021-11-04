<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\NewTenant;

class TenantsMigrateCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'new-tenant:migrate {tenant?} {--fresh} {--seed}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'create DB schema for new tenant';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        if ($this->argument('tenant')) {
            $this->migrate(
                NewTenant::find($this->argument('tenant'))
            );

        } else {
            NewTenant::all()->each(
                fn($tenant) => $this->migrate($tenant)
            );
        }
    }

    public function migrate($tenant)
    {
        $tenant->configure()->use();

        $this->line('');
        $this->line("-----------------------------------------");
        $this->info("Migrating Tenant #{$tenant->id} ({$tenant->name})");
        $this->line("-----------------------------------------");

        $options = ['--force' => true];

        if ($this->option('seed')) {
            $options['--seed'] = true;
        }

        $this->call(
            $this->option('fresh') ? 'migrate:fresh' : 'migrate',
            $options
        );
    }
}
