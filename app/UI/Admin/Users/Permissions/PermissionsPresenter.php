<?php

declare(strict_types=1);

namespace App\UI\Admin\Users\Permissions;

use App\UI\Accessory\FormFactory;
use App\UI\Accessory\RequireLoggedUser;
use Nette;

/**
 * @property UsersTemplate $template
 */
final class PermissionsPresenter extends Nette\Application\UI\Presenter
{
    // Incorporates methods to check user login status
    use RequireLoggedUser;

    public string $backlink;
    protected $user_data;

    public function __construct(private FormFactory $formFactory)
    {
    }

    public function renderAdd(): void
    {
    }

    public function renderDelete(): void
    {
    }
}
