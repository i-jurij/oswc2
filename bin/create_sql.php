<?php

$roles = 'CREATE TABLE IF NOT EXISTS "roles" 
	(	"id" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, 
		"role_name" TEXT UNIQUE  ON CONFLICT ROLLBACK NOT NULL
	)';
$permissions = 'CREATE TABLE IF NOT EXISTS "permissions" 
	(	"id" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
		"perm_mod" TEXT KEY NOT NULL, "perm_desc" TEXT NOT NULL
	)';
$roles_permissions = 'CREATE TABLE IF NOT EXISTS "roles_permissions" 
	(	"role_id" INTEGER NOT NULL, 
		"perm_id" INTEGER NOT NULL,
		PRIMARY KEY ("role_id", "perm_id")
	)';
$users = 'CREATE TABLE IF NOT EXISTS "users" 
	(	"id" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, 
		"username" TEXT NOT NULL UNIQUE ON CONFLICT ROLLBACK, 
		"password" TEXT NOT NULL, 
		"phone" TEXT UNIQUE ON CONFLICT ROLLBACK, 
		"phone_verified" INTEGER DEFAULT 0,
		"email" TEXT UNIQUE ON CONFLICT ROLLBACK, 
		"email_verified" INTEGER DEFAULT 0, 
		"auth_token" INTEGER, 
		"role" TEXT,
		"created_at" TEXT NOT NULL DEFAULT current_timestamp,
		"updated_at" TEXT NOT NULL DEFAULT current_timestamp
	)';
$users_updated_at_trigger = 'CREATE TRIGGER update_users_updated_at
		AFTER UPDATE ON users
		WHEN old.updated_at <> current_timestamp
		BEGIN
			UPDATE users
			SET updated_at = CURRENT_TIMESTAMP
			WHERE id = OLD.id;
		END';
$clients = 'CREATE TABLE IF NOT EXISTS "clients" 
	(	"id" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, 
		"name" TEXT, 
		"phone" TEXT NOT NULL UNIQUE ON CONFLICT ROLLBACK, 
		"phone_verified" INTEGER DEFAULT 0,
		"email" UNIQUE ON CONFLICT ROLLBACK, 
		"email_verified" INTEGER DEFAULT 0,
		"auth_token" INTEGER, 
		"created_at" TEXT NOT NULL DEFAULT current_timestamp,
		"updated_at" TEXT NOT NULL DEFAULT current_timestamp
	)';
$cliens_updated_at_trigger = 'CREATE TRIGGER update_clients_updated_at
		AFTER UPDATE ON clients
		WHEN old.updated_at <> current_timestamp
		BEGIN
			UPDATE clients
			SET updated_at = CURRENT_TIMESTAMP
			WHERE id = OLD.id;
		END';
$pages = 'CREATE TABLE IF NOT EXISTS pages
	(	"id" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, 
		"alias" VARCHAR(100) UNIQUE  ON CONFLICT ROLLBACK NOT NULL,
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
		"admin" INTEGER,
		"created_at" TEXT NOT NULL DEFAULT current_timestamp,
		"updated_at" TEXT NOT NULL DEFAULT current_timestamp
	)';
$pages_updated_at_trigger = 'CREATE TRIGGER update_pages_updated_at
		AFTER UPDATE ON pages
		WHEN old.updated_at <> current_timestamp
		BEGIN
			UPDATE pages
			SET updated_at = CURRENT_TIMESTAMP
			WHERE id = OLD.id;
		END';
$create_sqls = [
    'roles' => $roles,
    'permissions' => $permissions,
    'roles_permissions' => $roles_permissions,
    'users' => $users,
    'users_updated_at_trigger' => $users_updated_at_trigger,
    'pages' => $pages,
    'pages_updated_at_trigger' => $pages_updated_at_trigger,
    'clients' => $clients,
    'cliens_updated_at_trigger' => $cliens_updated_at_trigger,
];
