<?php

namespace App\UI\Sign;

use Nette;
use Nette\Application\UI\Form;
use Nette\Utils\Html;

final class SignPresenter extends Nette\Application\UI\Presenter
{
    protected function createComponentForm(): Form
    {
        $form = new Form();

        // setup custom rendering
        $renderer = $form->getRenderer();
        $renderer->wrappers['group']['container'] = 'div class="bgcontent shadow round m1 p2"';
        $renderer->wrappers['controls']['container'] = 'div';
        $renderer->wrappers['pair']['container'] = 'div';
        $renderer->wrappers['label']['container'] = 'p';
        $renderer->wrappers['control']['container'] = null;

        $form->addGroup('--- 👥 ---');

        $form->addText('username', 'Имя:')
            ->setRequired('Пожалуйста, введите ваше имя.')
            ->addRule($form::MinLength, 'Имя длиной не менее %d символов', 3)
            ->addRule($form::Pattern, 'Имя только из букв, цифр, дефисов и подчеркиваний', '^[a-zA-Zа-яА-ЯёЁ0-9\-_]{3,25}$')
            ->setMaxLength(25);

        $form->addPassword('password', 'Пароль:')
            ->setRequired('Пожалуйста, введите ваш пароль.')
            ->addRule($form::MinLength, 'Пароль длиной не менее %d символов', 4)
            ->setMaxLength(120);

        return $form;
    }

    protected function createComponentSignInForm(): Form
    {
        $form = $this->createComponentForm();
        $form->setHtmlAttribute('id', 'enter_to_admin')
            ->setHtmlAttribute('class', 'form');

        // $form->addHidden('userid');

        $form->addSubmit('send', 'Войти');

        $form->addGroup('--- ✍ ---');
        $url_reg = $this->link('Sign:up');
        $form->addButton('signup', Html::el('div')->setHtml('<a href="'.$url_reg.'">Зарегистрироваться</a>'))
            ->setHtmlAttribute('class', 'pseudo');

        $form->addGroup('--- § ---');
        $url_politic = $this->link('Home:politic');
        $form->addButton('politic', Html::el('div')->setHtml('<a href="'.$url_politic.'">Политика обработки персональных данных</a>'))
            ->setHtmlAttribute('class', 'pseudo');

        $form->onSuccess[] = $this->userLogin(...);

        return $form;
    }

    public function createComponentSignUpForm()
    {
        $form = $this->createComponentForm();

        $form->setHtmlAttribute('id', 'signup')
            ->setHtmlAttribute('class', 'form');

        $form->addPassword('passwordVerify', 'Повторите пароль:')
            ->setRequired('Введите пароль ещё раз, чтобы проверить опечатку')
            ->addRule($form::Equal, 'Несоответствие пароля', $form['password'])
            ->addRule($form::MinLength, 'Пароль длиной не менее %d символов', 4)
            ->setMaxLength(120)
            ->setOmitted();

        $form->addEmail('email', 'Эл.почта:')
            ->setEmptyValue('user@user.com');

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
            // create if not exist tables users, roles, permissions
            $this->getUser()->login($data->username, $data->password);
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
        $this->getUser()->logout();
        $this->flashMessage('Вы вышли.');
        $this->redirect('Home:');
    }
}
