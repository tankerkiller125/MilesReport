<?php

namespace App\Console\Commands;

use App\Jobs\CreateReport;
use App\User;
use Illuminate\Console\Command;

class SendUserReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'milesreport:send-user-report {user?} {--force}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send user reports';

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
     * @return mixed
     */
    public function handle()
    {
        if ($this->option('force')) {
            $force = true;
        } else {
            $force = false;
        }
        if ($this->argument('user') == null) {
            try {
                $users = User::all();
                foreach ($users as $user) {
                    dispatch((new CreateReport($user, $force))->onQueue('reports'));
                }
                $this->info('Reports generated and sent!');
            } catch (\Exception $e) {
                $this->error($e->getMessage());
            }
        } else {
            try {
                $user = User::whereId($this->argument('user'))->first();
                dispatch((new CreateReport($user, $force))->onQueue('reports'));
                $this->info('Reports generated and sent!');
            } catch (\Exception $e) {
                $this->error($e->getMessage());
            }
        }
    }
}
