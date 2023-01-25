<?php

namespace App\Console\Commands\Repositories;

use Illuminate\Console\Command;
use Tagd\Core\Repositories\Interfaces\Items\Items as ItemsRepo;

class Items extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tagd:repo:items';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '';

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
     * @param  Tagd\Core\Repositories\Interfaces\Items  $repo
     * @return int
     */
    public function handle(ItemsRepo $repo)
    {
        try {
            $repo->isAuthorizationEnabled(false);
            $all = $repo->all();
            dd($all);
        } catch (\Exception $e) {
            $this->error($e->getMessage());

            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }
}
