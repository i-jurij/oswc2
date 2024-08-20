<?php

declare(strict_types=1);

namespace App\UI\Admin\Cms\PageCreator;

use Nette;

final class PageCreatorPresenter extends Nette\Application\UI\Presenter
{
    use \App\UI\Accessory\RequireLoggedUser;
    use \App\UI\Accessory\ScanDirectoryRecursively;

    public function renderDefault()
    {
        $this->template->menuList = $this->scanDirectoryRecursively(__DIR__);
    }
}
