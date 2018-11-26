<?php

namespace App\Console\Commands;

use App\Role;
use App\User;
use Illuminate\Console\Command;

class InitialiseApp extends Command
{
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'skd:init {email : The email address of the admin} {password : The password for the admin}';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Initialise the application with a new user';

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

    $this->makeRoles();

    $user = User::where('email', $email)->first();
    if (!$user) {
      $user = User::create([
        'email' => $email,
        'password' => $password,
        'username' => str_slug($email),
        'name' => 'admin',
      ]);
      $user->assignRole('admin');

      $this->info("Created new admin user with email $email");

      return;
    }

    $this->info("User with email $email already exists. Done nothing.");
  }

  private function makeRoles()
  {
    $roles = collect(User::$base_roles)->map(function ($role, $slug) {
      if (!Role::where('name', $slug)->count()) {
        return Role::create(['name' => $slug, 'display_name' => $role]);
      }
    });
    $this->info('Created Roles: ' . $roles->pluck('display_name')->implode(', '));
  }
}
