<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Role;
use App\Models\Permission;

class GenerateAkses extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:akses';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $roles = Role::all();
        foreach($roles as $role){
            $permissions = Permission::updateOrCreate(
                [
                    'name' => $role->name,
                ],
                [
                    'display_name' => $role->display_name,
                    'description' => $role->description,
                ]
            );
            $role->permissions()->sync($permissions);
        }
        return Command::SUCCESS;
    }
}
