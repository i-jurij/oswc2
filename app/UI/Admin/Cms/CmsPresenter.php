<?php

declare(strict_types=1);

namespace App\UI\Admin\Cms;

use App\UI\Accessory\AdminCmsMenu;
use App\UI\Accessory\RequireLoggedUser;
use Nette;
use Nette\Security\User;

final class CmsPresenter extends Nette\Application\UI\Presenter
{
    // Incorporates methods to check user login status
    use RequireLoggedUser;
    use AdminCmsMenu;

    public function renderDefault()
    {
        $this->template->pageList = $this->getDirTree();
    }
}
