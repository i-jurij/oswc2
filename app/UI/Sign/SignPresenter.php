<?php

namespace App\UI\Sign;

use App\Model\UserFacade;
use App\UI\Accessory\FormFactory;
use Nette;
use Nette\Application\Attributes\Persistent;
use Nette\Application\UI\Form;
use Nette\Utils\Html;

final class SignPresenter extends Nette\Application\UI\Presenter
{
    /**
     * Stores the previous page hash to redirect back after successful login.
     */
    #[Persistent]
    public string $backlink = '';

    // Dependency injection of form factory and user management facade
    public function __construct(
        private UserFacade $userFacade,
        private FormFactory $formFactory
    ) {
    }

    protected function createComponentSignInForm(): Form
    {
        $form = $this->formFactory->createLoginForm();
        $form->setHtmlAttribute('id', 'enter_to_admin')
            ->setHtmlAttribute('class', 'form');

        $form->addGroup('');
        // $form->addCaptcha('captcha', 'Captcha error. Re-enter captcha.');

        $form->addGroup('');
        $form->addSubmit('send', 'Войти');

        $form->addGroup('--- § ---');
        $url_politic = $this->link('Home:politic');
        $form->addButton('politic', Html::el('div')->setHtml('<a href="'.$url_politic.'">Политика обработки персональных данных</a>'))
            ->setHtmlAttribute('class', 'pseudo');

        $form->onSuccess[] = $this->userLogin(...);

        return $form;
    }

    public function createComponentSignUpForm()
    {
        $form = $this->formFactory->createLoginForm();

        $form->setHtmlAttribute('id', 'signup')
            ->setHtmlAttribute('class', 'form');

        $form->addPassword('passwordVerify', '')
            ->setHtmlAttribute('placeholder', 'Confirm password:')
            ->setRequired('Введите пароль ещё раз, чтобы проверить опечатку')
            ->addRule($form::Equal, 'Несоответствие пароля', $form['password'])
            ->addRule($form::MinLength, 'Пароль длиной не менее %d символов', $this->userFacade::PasswordMinLength)
            ->setMaxLength(120)
            ->setOmitted();

        $form->addEmail('email', '')
            ->setHtmlAttribute('placeholder', 'Email:');

        $form->addGroup('');
        $form->addCaptcha('captcha', 'Captcha error. Re-enter captcha.');

        $form->addGroup('');
        $form->addSubmit('send', 'Зарегистрироваться');

        $form->addGroup('--- 🔓 ---');
        $url_reg = $this->link('Sign:in');
        $form->addButton('register', Html::el('div')->setHtml('<a href="'.$url_reg.'">Войти</a>'))
            ->setHtmlAttribute('class', 'pseudo');

        $form->addGroup('--- § ---');
        $url_politic = $this->link('Home:politic');
        $form->addButton('politic', Html::el('div')->setHtml('<a href="'.$url_politic.'">Политика обработки персональных данных</a>'))
            ->setHtmlAttribute('class', 'pseudo');

        $form->onSuccess[] = $this->processSignUpForm(...);

        return $form;
    }

    private function userLogin(Form $form, \stdClass $data): void
    {
        try {
            $this->getUser()->login($data->username, $data->password);
            $this->restoreRequest($this->backlink);
            /*
            if ($this->getUser()->isInRole('user')) {
                $this->redirect('Home:');
            } elseif ($this->getUser()->isInRole('master')
                || $this->getUser()->isInRole('moder')
                || $this->getUser()->isInRole('admin')
            ) {
                $this->redirect('Admin:');
            }
            */

            $this->redirect('Admin:');
        } catch (Nette\Security\AuthenticationException $e) {
            $form->addError('Неправильные логин или пароль.');
        }
    }

    private function processSignUpForm(Form $form, \stdClass $data): void
    {
        try {
            // register user
            $this->flashMessage('На ваш электронный адрес выслано письмо. Для завершения регистрации следуйте инструкции в письме.', 'info');
        } catch (Exception $e) {
            $form->addError('Unknown error.');
        }
    }

    public function actionOut(): void
    {
        $this->getUser()->logout(true);
        $this->flashMessage('Вы вышли.');
        $this->redirect('Home:');
        // $this->forward('Home:');
    }
}
