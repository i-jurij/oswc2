<?php

declare(strict_types=1);

namespace App\UI\Admin\Users;

use App\Model\UserFacade;
use App\UI\Accessory\RequireLoggedUser;
use Nette;

/**
 * @property UsersTemplate $template
 */
final class UsersPresenter extends Nette\Application\UI\Presenter
{
    // Incorporates methods to check user login status
    use RequireLoggedUser;

    public function __construct(protected UserFacade $userfacade)
    {
    }

    public function renderDefault()
    {
        try {
            $this->template->users_data = $this->userfacade->getAllUsersData();
        } catch (Exception $e) {
            $this->flashMessage($e->getMessage(), 'warning');
        }
    }

    public function actionProfile($id)
    {
        $this->template->user_data = $this->getUser()->getIdentity()->getData();
    }

    public function actionProfileUpdate($id)
    {
        // update profile throw UserFacade? and show profile again with updated data;
    }

    protected function getUsersData()
    {
    }
}
/*
class UsersTemplate extends Nette\Bridges\ApplicationLatte\Template
{
    public Nette\Security\User $user;
    public string $basePath;
    public string $baseUrl;
    public array $flashes;
    public object $presenter;
    public object $control;
    public array $pages_data;
}
*/
