<?php

namespace App\Console\Commands\Roles;

use App\Console\Commands\Roles\Traits\Helpers;
use Illuminate\Console\Command;
use Tagd\Core\Models\User\User;

class Remove extends Command
{
    use Helpers;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'roles:remove {email}';

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

        $actor = $this->askForActor(
            $this->askForRole()
        );

        try {
            $user->stopActingAs($actor);
        } catch (\Exception $e) {
            $this->error($e->getMessage());

            return Command::FAILURE;
        }

        $this->info('done');

        return Command::SUCCESS;
    }
}
