<?php

declare(strict_types=1);

namespace App\UI\Admin\Cms\PageCreator;

final class PageCreatorPresenter extends \App\UI\BasePresenter
{
    use \App\UI\Accessory\RequireLoggedUser;
    use \App\UI\Accessory\ScanDirectoryRecursively;

    public function renderDefault()
    {
        $this->template->menuList = $this->scanDirectoryRecursively(__DIR__);
    }
}
