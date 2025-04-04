<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class LeopordParcelStatusUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'Leopord:UpdateTrackingStatus';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Updating leopord status to local database';

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
        info('hellow world');
        return 0;
    }
}
