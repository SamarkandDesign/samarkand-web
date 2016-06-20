<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\User;

class CreateAdminUser extends Command
{
    /**
    * The name and signature of the console command.
    *
    * @var string
    */
    protected $signature = 'skd:create-admin {email : The email address of the admin} {password : The password for the admin}';

    /**
    * The console command description.
    *
    * @var string
    */
    protected $description = 'Create an admin user';

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
        $email = $this->argument('email');
        $password = $this->argument('password');

        $user = User::where('email', $email)->first();
        if (!$user) {
            $user = User::create([
                'email' => $email,
                'password' => $password,
                'username' => str_slug($email)
            ]);

            $this->info("Created new admin user with email $email");
            return;
        }

        $this->info("User with email $email already exists. Done nothing.");
    }
}
