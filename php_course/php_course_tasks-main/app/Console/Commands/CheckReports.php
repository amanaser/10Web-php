<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CheckReports extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'shop:check-reports {test?} {--queue=} {--bar}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command to check reported comments';

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
        $name = $this->ask('What is your name?');
        dump($this->arguments(), $this->options(), $name);
        $bar = $this->output->createProgressBar(50);
        for($i=0; $i<50; $i++ ) {
            sleep(1);
            $bar->advance(2);
        }
        $this->info('Here i am');
        return 1;
    }
}
