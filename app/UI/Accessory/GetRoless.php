<?php

namespace App\UI\Accessory;

trait GetRoless
{
    protected function prepare($explorer, $user_id)
    {
        $roles_ids = [];

        $roles_ids_sql = $explorer->table('role_user')
            ->select('role_id')
            ->where('user_id', $user_id);
        foreach ($roles_ids_sql as $role_id) {
            $roles_ids[] = $role_id['role_id'];
        }
        $roles_sql = $explorer->table('role')
            ->where('id', $roles_ids);

        return $roles_sql;
    }

    public function getRoless($explorer, $user_id)
    {
        $roles = [];
        foreach ($this->prepare($explorer, $user_id) as $role) {
            $roles[] = $role->role_name;
        }

        return $roles;
    }

    public function roleWithUserId($explorer, $user_id)
    {
        $roles = [];
        foreach ($this->prepare($explorer, $user_id) as $role) {
            $roles[$role->id] = $role->role_name;
        }

        return $roles;
    }
}
