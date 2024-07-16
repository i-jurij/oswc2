<?php

declare(strict_types=1);

namespace App\Core;

use Nette;
use Nette\Database\Connection;

class MyAuthorizator implements Nette\Security\Authorizator
{
    public function __construct(private Connection $sqlite)
    {
    }

    public function isAllowed($role, $resource, $action): bool
    {
        if ($role === 'admin') {
            return true;
        }

        $input = [
            'role_name' => $role,
            'resource' => $resource,
            'action' => $action,
        ];

        if (\in_array($input, $this->getRolePermissionData())) {
            return true;
        }

        return false;
    }

    private function getRolePermissionData(): array
    {
        $query = 'SELECT
                        r.role_name,
                        p.resource,
                        p.action
                    FROM
                        `role` AS r
                    INNER JOIN
                        `role_permission` AS rp
                        ON r.id = rp.role_id
                    INNER JOIN
                        `permission` AS p
                        ON p.id = rp.permission_id';

        foreach ($this->sqlite->query($query) as $row) {
            $result[] = [
                'role_name' => $row->role_name,
                'resource' => $row->resource,
                'action' => $row->action,
            ];
        }

        return $result;
    }
}
