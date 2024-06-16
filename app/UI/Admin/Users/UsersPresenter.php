<?php

declare(strict_types=1);

namespace App\UI\Admin\Users;

use App\Model\UserFacade;
use App\UI\Accessory\FormFactory;
use App\UI\Accessory\RequireLoggedUser;
use Nette;
use Nette\Application\UI\Form;

/**
 * @property UsersTemplate $template
 */
final class UsersPresenter extends Nette\Application\UI\Presenter
{
    // Incorporates methods to check user login status
    use RequireLoggedUser;

    public function __construct(protected UserFacade $userfacade,
        private FormFactory $formFactory)
    {
    }

    public function renderDefault(int $page = 1): void
    {
        $users_data = $this->userfacade->getAllUsersData();
        $lastPage = 0;
        $this->template->users_data = $users_data->page($page, 5, $lastPage);
        $this->template->page = $page;
        $this->template->lastPage = $lastPage;
    }

    public function actionProfile($id): void
    {
        $this->template->user_data = $this->getUser()->getIdentity()->getData();
    }

    public function actionProfileUpdate($id): void
    {
        // update profile throw UserFacade? and show profile again with updated data;
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

        $form->addText('role', 'Role:')
            ->setHtmlAttribute('placeholder', 'Role:')
            ->addRule($form::MinLength, '>= %d characters', 3)
            ->addRule($form::Pattern, 'Role only letters', '^[a-zA-Zа-яА-ЯёЁ]{3,25}$')
            ->setMaxLength(25);

        $form->addGroup('');
        $form->addSubmit('send', 'Add user');

        $form->onSuccess[] = [$this, 'add'];

        return $form;
    }

    public function add(Form $form, $data): void
    {
        // $data->name contains name
        // $data->password contains password
        $this->userfacade->add($data);
        $this->flashMessage('You have successfully user add.');
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
