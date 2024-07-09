<?php

declare(strict_types=1);

namespace App\Model;

use App\UI\Accessory\RequireLoggedUser;
use Nette\Database\Explorer;
use Nette\Database\Table\Selection;

/**
 * Manages user-related operations such as authentication and adding new users.
 */
final class RoleFacade
{
    use RequireLoggedUser;
    public Selection $table;
    private Selection $role;
    private Selection $role_permission;

    public function __construct(public Explorer $sqlite)
    {
        $this->role = $this->sqlite->table('role');
        $this->role_permission = $this->sqlite->table('role_permission');
    }

    public function add(array $data): string
    {
        $role = $this->role->insert(['role_name' => $data['rolename']]) or throw new Exception('Data NOT insert to table "role"');

        if (!empty($data['permissions'][0]) && isset($role->id)) {
            foreach ($data['permissions'] as $key => $permission_id) {
                $role_permission_insert[] = ['role_id' => $role->id, 'permission_id' => $permission_id];
            }
            $role_permission = $this->role_permission->insert($role_permission_insert) or throw new Exception('Data NOT insert to table "role_permission"');
        }

        return $role->role_name;
    }
}
