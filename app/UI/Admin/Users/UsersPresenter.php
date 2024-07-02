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

    public string $backlink;
    protected $user_data;

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

    public function renderProfile(): void
    {
        $identity = $this->getUser()->getIdentity();
        $this->template->user_data = $identity->getData();
        $this->template->user_data['roles'] = $identity->getRoles();
    }

    public function createComponentUserUpdateForm()
    {
        $form = new Form();
        $form->addProtection();
        $renderer = $form->getRenderer();
        $renderer->wrappers['group']['container'] = 'div class="my1 mx-auto pb2 px2"';
        $renderer->wrappers['controls']['container'] = 'div';
        $renderer->wrappers['pair']['container'] = 'div';
        $renderer->wrappers['label']['container'] = null;
        $renderer->wrappers['control']['container'] = null;

        $form->setHtmlAttribute('id', 'userUpdateForm')
            ->setHtmlAttribute('class', 'form')
            ->setAction($this->link(':Admin:Users:update'));

        $form->addGroup('');

        $form->addHidden('id');

        $form->addText('username', 'Username:')
            ->setHtmlAttribute('placeholder', 'Name:')
            ->addRule($form::MinLength, 'Имя длиной не менее %d символов', 3)
            ->addRule($form::Pattern, 'Имя только из букв, цифр, дефисов и подчеркиваний', '^[a-zA-Zа-яА-ЯёЁ0-9\-_]{3,25}$')
            ->setMaxLength(25);

        $form->addPassword('password', 'Password:')
            ->setHtmlAttribute('placeholder', 'Password:')
            ->addRule($form::MinLength, 'Пароль длиной не менее %d символов', $this->userfacade::PasswordMinLength)
            ->setMaxLength(120);

        $form->addPassword('passwordVerify', 'PasswordVerify')
            ->setHtmlAttribute('placeholder', 'Confirm password:')
            ->addRule($form::Equal, 'Несоответствие пароля', $form['password'])
            ->addRule($form::MinLength, 'Пароль длиной не менее %d символов', $this->userfacade::PasswordMinLength)
            ->setMaxLength(120)
            ->setOmitted();

        $form->addText('phone', 'Phone:')
            ->setHtmlType('tel')
            ->setHtmlAttribute('placeholder', 'Phone:')
            ->addRule($form::Pattern, '+7 000 111 22 33', '(\+?7|8)?\s?[\(]{0,1}?\d{3}[\)]{0,1}\s?[\-]{0,1}?\d{1}\s?[\-]{0,1}?\d{1}\s?[\-]{0,1}?\d{1}\s?[\-]{0,1}?\d{1}\s?[\-]{0,1}?\d{1}\s?[\-]{0,1}?\d{1}\s?[\-]{0,1}?\d{1}\s?[\-]{0,1}?');
        // ->setEmptyValue('+7');

        $form->addEmail('email', 'Email:')
            ->setHtmlAttribute('placeholder', 'Email:');

        $roles = $this->userfacade->sqlite->table('role');
        foreach ($roles as $role) {
            $roles_array[$role['id']] = $role['role_name'];
        }

        $form->addCheckboxList('roles', 'Roles:', $roles_array);

        $form->addGroup('');
        $form->addSubmit('send', 'Update user');

        $form->onSuccess[] = [$this, 'update'];

        return $form;
    }

    public function renderEdit(int $id): void
    {
        $this->template->user_data = $this->userfacade->getUserData($id);
        $this->template->user_roles = $this->roleWithUserId($this->userfacade->sqlite, $id);
    }

    public function update(Form $form, $data): void
    {
        // update profile throw UserFacade? and show profile again with updated data;
        try {
            if (!empty($data)) {
                $this->userfacade->update($data);
                // $this->flashMessage('User "'.$this->userfacade->getUserData($this->update_user_id)->username.'" updated', 'text-success');
                $this->flashMessage('User updated', 'text-success');
            } else {
                $this->flashMessage('Nothing was updated', 'text-success');
            }
        } catch (\Exception $e) {
            $this->flashMessage('Caught Exception!'.PHP_EOL
                .'Error message: '.$e->getMessage().PHP_EOL
                .'File: '.$e->getFile().PHP_EOL
                .'Line: '.$e->getLine().PHP_EOL
                .'Error code: '.$e->getCode().PHP_EOL
                .'Trace: '.$e->getTraceAsString().PHP_EOL, 'text-danger');
        }
        $this->redirect(':Admin:Users:');
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

        $form->addPassword('passwordVerify', 'PasswordVerify')
            ->setHtmlAttribute('placeholder', 'Confirm password:')
            ->setRequired('Введите пароль ещё раз, чтобы проверить опечатки')
            ->addRule($form::Equal, 'Несоответствие пароля', $form['password'])
            ->addRule($form::MinLength, 'Пароль длиной не менее %d символов', $this->userfacade::PasswordMinLength)
            ->setMaxLength(120)
            ->setOmitted();

        $form->addText('phone', 'Phone:')
            ->setHtmlType('tel')
            ->setHtmlAttribute('placeholder', 'Phone:')
            ->addRule($form::Pattern, '+7 000 111 22 33', '(\+?7|8)?\s?[\(]{0,1}?\d{3}[\)]{0,1}\s?[\-]{0,1}?\d{1}\s?[\-]{0,1}?\d{1}\s?[\-]{0,1}?\d{1}\s?[\-]{0,1}?\d{1}\s?[\-]{0,1}?\d{1}\s?[\-]{0,1}?\d{1}\s?[\-]{0,1}?\d{1}\s?[\-]{0,1}?');

        $form->addEmail('email', 'Email:')
            ->setHtmlAttribute('placeholder', 'Email:');

        $roles = $this->userfacade->sqlite->table('role');
        foreach ($roles as $role) {
            $roles_array[$role['id']] = $role['role_name'];
        }

        $form->addCheckboxList('roles', 'Roles:', $roles_array);

        $form->addGroup('');
        $form->addSubmit('send', 'Add user');

        $form->onSuccess[] = [$this, 'add'];

        return $form;
    }

    public function add(Form $form, $data): void
    {
        // $data->name contains name, $data->password contains password
        try {
            $this->userfacade->add($data);
            $this->flashMessage('You have successfully user add.', 'text-success');
        } catch (\Exception $e) {
            $this->flashMessage("Such a name, email or number is already in the database.\nError: ".$e->getMessage(), 'text-danger');
        }

        $this->redirect(':Admin:Users:');
    }
}
