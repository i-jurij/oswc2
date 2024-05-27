<?php

namespace App\UI\Sign;

use Nette;
use Nette\Application\UI\Form;

final class SignPresenter extends Nette\Application\UI\Presenter
{
    protected function createComponentSignInForm(): Form
    {
        $form = new Form();

        // setup custom rendering
        $renderer = $form->getRenderer();
        $renderer->wrappers['group']['container'] = null;
        $renderer->wrappers['controls']['container'] = 'div';
        $renderer->wrappers['pair']['container'] = 'div';
        $renderer->wrappers['label']['container'] = 'p';
        $renderer->wrappers['control']['container'] = null;
        $renderer->wrappers['control']['.odd'] = 'odd';

        $form->setHtmlAttribute('id', 'enter_to_admin')
            ->setHtmlAttribute('class', 'form');
        $form->addText('username', 'Имя:')
            ->setRequired('Пожалуйста, введите ваше имя.')
            ->addRule($form::MinLength, 'Имя длиной не менее %d символов', 3)
            ->addRule($form::Pattern, 'Имя только из букв, цифр, дефисов и подчеркиваний', '^[a-zA-Zа-яА-ЯёЁ0-9\-_]{3,25}$')
            ->setMaxLength(25);

        $form->addPassword('password', 'Пароль:')
            ->setRequired('Пожалуйста, введите ваш пароль.')
            ->addRule($form::MinLength, 'Пароль длиной не менее %d символов', 4)
            ->setMaxLength(120);

        $form->addSubmit('send', 'Войти');

        $form->onSuccess[] = $this->processForm(...);

        return $form;
    }

    private function processForm(Form $form, \stdClass $data): void
    {
        try {
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

    public function actionOut(): void
    {
        $this->getUser()->logout();
        $this->flashMessage('Вы вышли.');
        $this->redirect('Home:');
    }
}
