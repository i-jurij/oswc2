<?php

declare(strict_types=1);

namespace App\UI\Accessory;

use Nette\Utils\Finder;

trait LinkFromFileSystem
{
    private function linkFromFileSystem(string $directory)
    {
        $directory_tree = [];

        $directory_scan = (new Finder())->directories()->in($directory);

        foreach ($directory_scan as $value) {
            $path = $value->getRealPath();
            $array_from_path = \explode('app'.DIRECTORY_SEPARATOR.'UI', $path);
            $smc_path = array_pop($array_from_path);
            $namespace = 'App\UI'.\implode('\\', explode(DIRECTORY_SEPARATOR, $smc_path));
            $name = $value->getFileName();
            $class = $namespace.'\\'.$name.'Presenter';
            $check_class = class_exists($class, true);
            $link = \implode(':', explode(DIRECTORY_SEPARATOR, $smc_path)).':';
            if ($check_class) {
                $ar = $this->linkFromFileSystem($path);
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
