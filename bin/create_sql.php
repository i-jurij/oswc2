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
		"created_at" INTEGER,
		"updated_at" INTEGER
	)';
$clients = 'CREATE TABLE IF NOT EXISTS "clients" 
	(	"id" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, 
		"name" TEXT, 
		"phone" TEXT NOT NULL UNIQUE ON CONFLICT ROLLBACK, 
		"phone_verified" INTEGER DEFAULT 0,
		"email" UNIQUE ON CONFLICT ROLLBACK, 
		"email_verified" INTEGER DEFAULT 0,
		"auth_token" INTEGER, 
		"created_at" INTEGER,
		"updated_at" INTEGER
	)';
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
		"created_at" INTEGER,
		"updated_at" INTEGER
	)';

$create_sqls = [
    'roles' => $roles,
    'permissions' => $permissions,
    'roles_permissions' => $roles_permissions,
    'users' => $users,
    'pages' => $pages,
    'clients' => $clients,
];
