<?php

declare(strict_types=1);

namespace App\UI\Accessory;

use Nette\Utils\Finder;

trait ScanDirectoryRecursively
{
    private function scanDirectoryRecursively($directory)
    {
        $directory_tree = [];

        $directory_scan = (new Finder())->directories()->in($directory);

        foreach ($directory_scan as $value) {
            $path = $value->getRealPath();
            list(, $smc_path) = \explode('Admin'.DIRECTORY_SEPARATOR.'Cms', $path);
            $namespace = 'App\UI\Admin\Cms'.\implode('\\', explode(DIRECTORY_SEPARATOR, $smc_path));
            $name = $value->getFileName();
            $class = $namespace.'\\'.$name.'Presenter';
            $check_class = class_exists($class, true);
            $link = ':Admin:Cms'.\implode(':', explode(DIRECTORY_SEPARATOR, $smc_path)).':';
            if ($check_class) {
                $ar = $this->scanDirectoryRecursively($path);
                if (\iterator_count($ar) > 0) {
                    $directory_tree[$link] = $ar;
                } else {
                    $directory_tree[$link] = null;
                }
            }
        }

        return $directory_tree;
    }
}
