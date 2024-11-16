<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class UserProfileResource extends JsonResource
{
    private $permissions;

    public function __construct(array $permissions)
    {
        $this->permissions = $permissions;
    }
    /**
     * Transform the resource into an array.
     *
     * @param  mixed $request
     * @return array
     */
    public function toArray(Request $request): array
    {
        if ($request->is_admin) {
            $permissions = Auth::user()->getAllPermissions()->toArray() ?? [];
        } else {
            $permissions = $this->permissions;
        }
        $menus = $this->explodeAction($permissions);
        return [
            'user' => new UserResource(Auth::user()),
            'meta' => [
                'permissions' => $menus,
                'menus' => $this->convertMenu($menus, $request->is_admin),
            ],
        ];
    }

    /**
     * Explode Action
     *
     * @param  mixed $permissions
     * @return Collection
     */
    public function explodeAction($permissions)
    {
        $menus = explodePermission($permissions);
        $grouped = collect($menus)
            ->groupBy('resource') // Group by resource
            ->map(function ($items) {
                // Merge actions by resource
                return $items->pluck('actions')->flatten()->unique()->values();
            })->toArray();

        $grouped = array_map(function ($key, $value) {
            return [
                'resource' => $key,
                'action' => $value,
            ];
        }, array_keys($grouped), $grouped);
        return $grouped;
    }

    /**
     * Convert Menu
     *
     * @param  mixed $menus
     * @return void
     */
    public function convertMenu($menus, $isAdmin)
    {
        $menus = array_column($menus, 'resource') ?: [];
        if (empty($menus)) {
            return [];
        }
        $menuConfig = ($isAdmin ? config('erp-menus.admin') : config('erp-menus.employee')) ?? [];
        $menuConverted = array_map(fn($menu) => ['name' => $menu], array_intersect($menus, $menuConfig));
        return $menuConverted;
    }
}
