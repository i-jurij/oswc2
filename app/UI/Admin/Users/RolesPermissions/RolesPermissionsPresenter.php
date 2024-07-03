<?php

declare(strict_types=1);

namespace App\UI\Admin\Users\RolesPermissions;

use App\Model\UserFacade;
use App\UI\Accessory\FormFactory;
use App\UI\Accessory\GetRoless;
use App\UI\Accessory\RequireLoggedUser;
use Nette;

/**
 * @property UsersTemplate $template
 */
final class RolesPermissionsPresenter extends Nette\Application\UI\Presenter
{
    use GetRoless;
    // Incorporates methods to check user login status
    use RequireLoggedUser;

    public string $backlink;
    protected $user_data;

    public function __construct(protected UserFacade $userfacade,
        private FormFactory $formFactory)
    {
    }

    public function renderDefault(int $page = 1): void
    {
        $this->template->roles = $this->userfacade->sqlite->table('role');
    }
}
