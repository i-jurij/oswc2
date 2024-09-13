<?php

declare(strict_types=1);

namespace App\UI\Admin\Cms\PageCreator\Create;

use Nette\Security\User;

final class CreatePresenter extends \App\UI\BasePresenter
{
    // Incorporates methods to check user login status
    use \App\UI\Accessory\RequireLoggedUser;
    use \App\UI\Accessory\LinkFromFileSystem;

    public function renderDefault()
    {
        // for linkfromfilesystem
        $home_dir = APPDIR.DIRECTORY_SEPARATOR.'UI'.DIRECTORY_SEPARATOR.'Home';
        $this->template->homeTree = $this->linkFromFileSystem($home_dir);
        // for admincmsmenu
        $cms_dir = APPDIR.DIRECTORY_SEPARATOR.'UI'.DIRECTORY_SEPARATOR.'Admin'.DIRECTORY_SEPARATOR.'Cms';
        $this->template->cmsTree = $this->linkFromFileSystem($cms_dir);
    }
}
