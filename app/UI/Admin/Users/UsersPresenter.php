<?php

declare(strict_types=1);

namespace App\UI\Admin\Users;

use App\Model\UserFacade;
use App\UI\Accessory\FormFactory;
use App\UI\Accessory\GetRoless;
use App\UI\Accessory\RequireLoggedUser;
use Nette;
use Nette\Application\UI\Form;

/**
 * @property UsersTemplate $template
 */
final class UsersPresenter extends Nette\Application\UI\Presenter
{
    use GetRoless;
    // Incorporates methods to check user login status
    use RequireLoggedUser;

    public function __construct(protected UserFacade $userfacade,
        private FormFactory $formFactory)
    {
    }

    public function renderDefault(int $page = 1): void
    {
        $users_data = $this->userfacade->getAllUsersData();
        $this->template->count = count($users_data);

        $lastPage = 0;
        $this->template->users_data = $users_data->page($page, 8, $lastPage);
        $this->template->page = $page;
        $this->template->lastPage = $lastPage;

        foreach ($users_data as $user) {
            $roles[$user->id] = $this->roleWithUserId($this->userfacade->sqlite, $user->id);
        }
        $this->template->users_roles = $roles;
    }

    public function actionProfile(): void
    {
        $this->template->user_data = $this->getUser()->getIdentity()->getData();
    }

    public function actionEdit(int $id): void
    {
        $this->template->user_data = $this->userfacade->getUserData($id);
    }

    public function actionUpdate($id): void
    {
        // update profile throw UserFacade? and show profile again with updated data;
    }

    public function actionDelete(int $id): void
    {
        try {
            $this->userfacade->deleteUserData($id);
            $this->flashMessage('User deleted.');
        } catch (\Throwable $th) {
            $this->flashMessage($th);
        }
        $this->redirect(':Admin:Users:');
    }

    public function createComponentUserAddForm()
    {
        $form = $this->formFactory->createLoginForm();

        $form->setHtmlAttribute('id', 'useradd')
            ->setHtmlAttribute('class', 'form');

        $form->addPassword('passwordVerify', '')
            ->setHtmlAttribute('placeholder', 'Confirm password:')
            ->setRequired('Введите пароль ещё раз, чтобы проверить опечатки')
            ->addRule($form::Equal, 'Несоответствие пароля', $form['password'])
            ->addRule($form::MinLength, 'Пароль длиной не менее %d символов', $this->userfacade::PasswordMinLength)
            ->setMaxLength(120)
            ->setOmitted();

        $form->addText('phone', 'Phone:')
            ->setHtmlType('tel');
        // ->setEmptyValue('+7');

        $form->addEmail('email', '');

        $roles = $this->userfacade->sqlite->table('role');
        foreach ($roles as $role) {
            $roles_array[$role['id']] = $role['role_name'];
        }

        $form->addCheckboxList('roles', 'Roles:', $roles_array);

        /*
        $form->addText('role', 'Role:')
        ->setHtmlAttribute('placeholder', 'Role:')
        ->addRule($form::MinLength, '>= %d characters', 3)
        ->addRule($form::Pattern, 'Role only letters', '^[a-zA-Zа-яА-ЯёЁ]{3,25}$')
        ->setMaxLength(25);
        */

        $form->addGroup('');
        $form->addSubmit('send', 'Add user');

        $form->onSuccess[] = [$this, 'add'];

        return $form;
    }

    public function add(Form $form, $data): void
    {
        // $data->name contains name
        // $data->password contains password
        try {
            $this->userfacade->add($data);
            $this->flashMessage('You have successfully user add.', 'text-success');
        } catch (\Exception $e) {
            $this->flashMessage("Such a name, email or number is already in the database.\nError: ".$e->getMessage(), 'text-danger');
        }

        $this->redirect(':Admin:Users:');
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
