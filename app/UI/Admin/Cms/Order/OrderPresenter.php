<?php

declare(strict_types=1);

namespace App\UI\Admin\Cms\Order;

use Nette;
use Nette\Security\User;

final class OrderPresenter extends Nette\Application\UI\Presenter
{
    // Incorporates methods to check user login status
    use \App\UI\Accessory\RequireLoggedUser;
    use \App\UI\Accessory\ScanDirectoryRecursively;

    public function renderDefault()
    {
        $this->template->menuList = $this->scanDirectoryRecursively(__DIR__);
    }
}
