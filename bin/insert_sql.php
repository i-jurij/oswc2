<?php

$roles = 'INSERT INTO "roles" ("role_name") VALUES
		("client"),
		("user"),
		("moder"),
		("admin")';
$permissions = 'INSERT INTO "permissions" ("perm_mod", "perm_desc") VALUES
		("USER", "Get"),
		("USER", "Save"),
		("USER", "Delete")';
$roles_permissions = 'INSERT INTO "roles_permissions" ("role_id", "perm_id") VALUES
		((SELECT "id" from "roles" WHERE "role_name"="admin" LIMIT 1), 1),
		((SELECT "id" from "roles" WHERE "role_name"="admin" LIMIT 1), 2),
		((SELECT "id" from "roles" WHERE "role_name"="admin" LIMIT 1), 3),
		((SELECT "id" from "roles" WHERE "role_name"="moder" LIMIT 1), 1)';

$insert_sqls = [
    'roles' => $roles,
    'permissions' => $permissions,
    'roles_permissions' => $roles_permissions,
];
