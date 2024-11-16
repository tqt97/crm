<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use App\Models\Permission;

class PermissionsSeeder extends Seeder
{
    const API = 'api';
    const WEB = 'web';
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // getting config from each Modules
        $config = config('erp-permissions');
        if (is_array($config)) {
            // fetch Permission (for API)
            foreach (Arr::get($config, self::API, []) as $roles) {
                // fetch Roles
                if (is_array($roles)) {
                    // fetch Controllers
                    foreach ($roles as $controllers) {
                        if (is_array($controllers)) {
                            foreach ($controllers as $permission => $function) {
                                if ($this->isDuplicate($permission)) {
                                    continue;
                                }
                                Permission::create([
                                    'name' => trim($permission),
                                    'guard_name' => self::API,
                                ]);
                            }
                        }
                    }
                }
            }
            // fetch Permission (for WEB)
            foreach (Arr::get($config, self::WEB, []) as $roles) {
                // fetch Roles
                if (is_array($roles)) {
                    // fetch Controllers
                    foreach ($roles as $controllers) {
                        if (is_array($controllers)) {
                            foreach ($controllers as $permission => $function) {
                                if ($this->isDuplicate($permission)) {
                                    continue;
                                }
                                Permission::create([
                                    'name' => trim($permission),
                                    'guard_name' => self::WEB,
                                ]);
                            }
                        }
                    }
                }
            }
        }
    }

    private function isDuplicate(string $permission)
    {
        return Permission::where('name', $permission)->first();
    }
}
