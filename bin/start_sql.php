<?php

$roles = 'CREATE TABLE IF NOT EXISTS "roles" ("id" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, "role_name" TEXT UNIQUE NOT NULL)';
$perms = 'CREATE TABLE IF NOT EXISTS "permissions" ( "id" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
	"perm_mod" TEXT KEY NOT NULL, "perm_desc" TEXT NOT NULL)';
$roles_perms = 'CREATE TABLE IF NOT EXISTS "roles_permissions" ("role_id" INTEGER NOT NULL, 
		"perm_id" INTEGER NOT NULL,
		PRIMARY KEY("role_id", "perm_id"))';
$users = 'CREATE TABLE IF NOT EXISTS "users" ("id" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, 
		"username" TEXT NOT NULL UNIQUE ON CONFLICT ROLLBACK, 
		"password" TEXT NOT NULL, 
		"email" TEXT UNIQUE ON CONFLICT ROLLBACK, 
		"email_status" INTEGER DEFAULT 0, 
		"role_id" TEXT
	)';
$pages = 'CREATE TABLE IF NOT EXISTS pages(
                "id" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, 
                "alias" VARCHAR(100) UNIQUE NOT NULL,
                "route" VARCHAR(100),
                "path" VARCHAR(100), 
                "title" VARCHAR(100), 
                "description" VARCHAR(255), 
                "keywords" VARCHAR(500), 
                "robots" VARCHAR(100), 
                "img" TEXT, 
                "content" TEXT, 
                "published" INTEGER,
                "access_level" VARCHAR(10), 
                "admin" INTEGER)';

$insert_roles = 'INSERT INTO "roles" ("role_name") VALUES
		("client"),
		("user"),
		("moder"),
		("admin")';
$insert_perms = 'INSERT INTO "permissions" ("perm_mod", "perm_desc") VALUES
		("USER", "Get"),
		("USER", "Save"),
		("USER", "Delete")';
$insert_roles_perms = 'INSERT INTO "roles_permissions" ("role_id", "perm_id") VALUES
		((SELECT "id" from "roles" WHERE "role_name"="admin" LIMIT 1), 1),
		((SELECT "id" from "roles" WHERE "role_name"="admin" LIMIT 1), 2),
		((SELECT "id" from "roles" WHERE "role_name"="admin" LIMIT 1), 3),
		((SELECT "id" from "roles" WHERE "role_name"="moder" LIMIT 1), 1)';

$sqls = [
    $roles,
    $perms,
    $roles_perms,
    $users,
    $pages,
    $insert_roles,
    $insert_perms,
    $insert_roles_perms,
];
