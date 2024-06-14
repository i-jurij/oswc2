<?php

$users_updated_at_trigger = 'CREATE TRIGGER update_users_updated_at
		AFTER UPDATE ON users
		WHEN old.updated_at <> current_timestamp
		BEGIN
			UPDATE users
			SET updated_at = CURRENT_TIMESTAMP
			WHERE id = OLD.id;
		END';

$cliens_updated_at_trigger = 'CREATE TRIGGER update_clients_updated_at
		AFTER UPDATE ON clients
		WHEN old.updated_at <> current_timestamp
		BEGIN
			UPDATE clients
			SET updated_at = CURRENT_TIMESTAMP
			WHERE id = OLD.id;
		END';

$pages_updated_at_trigger = 'CREATE TRIGGER update_pages_updated_at
		AFTER UPDATE ON pages
		WHEN old.updated_at <> current_timestamp
		BEGIN
			UPDATE pages
			SET updated_at = CURRENT_TIMESTAMP
			WHERE id = OLD.id;
		END';

$trigger_sqls = [
    'users' => $users_updated_at_trigger,
    'pages' => $pages_updated_at_trigger,
    'cliens' => $cliens_updated_at_trigger,
];
