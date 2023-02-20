<?php

namespace App\Console\Commands\Roles;

use App\Console\Commands\Roles\Traits\Helpers;
use App\Models\User;
use Illuminate\Console\Command;

class All extends Command
{
    use Helpers;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'roles:all {email}';

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
     * @return int
     */
    public function handle()
    {
        $email = $this->argument('email');

        $user = User::where('email', $email)->firstOrFail();

        $this->showCurrentRoles($user);

        return Command::SUCCESS;
    }
}
