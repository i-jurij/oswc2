<?php

declare(strict_types=1);

namespace App\UI\Admin\Cms\Order\Update;

use App\UI\Accessory\RequireLoggedUser;
use Nette;
use Nette\Security\User;

final class UpdatePresenter extends Nette\Application\UI\Presenter
{
    // Incorporates methods to check user login status
    use RequireLoggedUser;

    public function renderDefault()
    {
    }
}
