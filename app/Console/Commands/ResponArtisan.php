<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ResponArtisan extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'respon:artisan {status} {title} {respon}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $respon = collect([
            'title' => $this->argument('title'),
            'message' => $this->argument('respon'),
            'status' => ($this->argument('status') == 'error') ? 'error' : 'success',
        ]);
        $this->{$this->argument('status')}($respon->toJson());
    }
}
