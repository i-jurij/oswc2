<?php

declare(strict_types=1);

namespace App\UI\Admin\Cms;

use App\UI\Accessory\RequireLoggedUser;
use Nette\Security\User;

final class CmsPresenter extends \App\UI\BasePresenter
{
    // Incorporates methods to check user login status
    use RequireLoggedUser;

    public function renderDefault()
    {
    }
}
