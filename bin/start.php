<?php

declare(strict_types=1);

if (PHP_SAPI === 'cli') {
    require __DIR__.'/../vendor/autoload.php';

    $container = App\Bootstrap::boot()
        ->createContainer();
    // var_dump($container);

    if (!isset($argv[1])) {
        echo '
            1. Create table "users", "role", "permissions", "role_permissions", "pages", run:
            "php start.php migrate"

            2. Create user (admin is needed), run:
            "php start.php useradd <username> <password> <role>"
            ';
        exit(1);
    } elseif ($argc == 2 && !empty($argv[1]) && $argv[1] === 'migrate') {
        try {
            $db = $container->getByName('database.sqlite.connection');

            $db->transaction(function ($db) {
                $path_create = APPDIR.'/../bin/create_sql.php';
                $path_insert = APPDIR.'/../bin/insert_sql.php';
                if (include $path_create) {
                    foreach ($create_sqls as $key => $sql) {
                        $check_table = $db->fetch('SELECT count(*) FROM sqlite_master WHERE type=? AND name=?', 'table', $key);
                        if ($check_table['count(*)'] === 0) {
                            $db->query($sql);
                            if ((include $path_insert) && isset($insert_sqls[$key])) {
                                $db->query($insert_sqls[$key]);
                            }
                        }
                    }
                }
            });

            echo "Migrate was executed. Database and table was created.\n";

            exit(1);
        } catch (Exception $e) {
            echo "Error: '.$e.'.\n";
            exit(1);
        }
    } elseif ($argc == 5 && $argv[1] === 'useradd') {
        [,, $name, $password, $role] = $argv;

        $manager = $container->getByType(App\Model\UserFacade::class);

        try {
            $manager->shortAdd($name, $password, $role);
            echo "User $name was added.\n";
        } catch (App\Model\DuplicateNameException $e) {
            echo "Error: duplicate name.\n";
            exit(1);
        }
    } else {
        echo "Somethig wrong :( \n";
        exit(1);
    }
}
