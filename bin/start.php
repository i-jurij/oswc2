<?php

declare(strict_types=1);

if (PHP_SAPI === 'cli') {
    require __DIR__.'/../vendor/autoload.php';

    $container = App\Bootstrap::boot()
        ->createContainer();

    if (!isset($argv[1])) {
        echo '
            1. Create database and table "users", "role", "permissions", "role_permissions", run:
            "php start.php migrate"

            2. Create user (admin is needed), run:
            "php start.php useradd <username> <password> <email>"
            ';
        exit(1);
    } elseif ($argc == 2 && !empty($argv[1]) && $argv[1] === 'migrate') {
        try {
            var_dump($argv[1]);

            // echo "Migrate was executed. Database and table was created.\n";
            exit(1);
        } catch (Exception $e) {
            echo "Error: '.$e.'.\n";
            exit(1);
        }
    } elseif ($argc == 5 && $argv[1] === 'useradd') {
        [,, $name, $password, $email] = $argv;

        $manager = $container->getByType(App\Model\UserFacade::class);

        try {
            $manager->add($name, $email, $password);
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
