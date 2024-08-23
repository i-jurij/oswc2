<?php

declare(strict_types=1);

namespace App\UI\Accessory;

trait AdminCmsMenu
{
    use LinkFromFileSystem;

    private string $cms_dir = APPDIR.DIRECTORY_SEPARATOR.'UI'.DIRECTORY_SEPARATOR.'Admin'.DIRECTORY_SEPARATOR.'Cms';

    private function getDirTree()
    {
        return $this->linkFromFileSystem($this->cms_dir);
    }
}
