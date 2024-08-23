<?php

declare(strict_types=1);

namespace App\UI\Admin\Cms\PageCreator;

final class PageCreatorPresenter extends \App\UI\BasePresenter
{
    use \App\UI\Accessory\RequireLoggedUser;
    use \App\UI\Accessory\LinkFromFileSystem;

    public function renderDefault()
    {
        if ($this->getUser()->isAllowed('PageCreator', 'update')) {
            $this->template->menuList = $this->linkFromFileSystem(__DIR__);
        }
    }
}
