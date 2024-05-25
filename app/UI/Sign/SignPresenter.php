<?php

namespace App\UI\Sign;

use Nette;
use Nette\Application\UI\Form;
use Nette\Utils\Html;

final class SignPresenter extends Nette\Application\UI\Presenter
{
    protected function createComponentSignInForm(): Form
    {
        $form = new Form();

        // setup custom rendering
        $renderer = $form->getRenderer();
        $renderer->wrappers['form']['container'] = Html::el('div')->id('form');
        $renderer->wrappers['group']['container'] = null;
        $renderer->wrappers['group']['label'] = 'h3';
        $renderer->wrappers['pair']['container'] = null;
        $renderer->wrappers['controls']['container'] = 'dl';
        $renderer->wrappers['control']['container'] = 'dd';
        $renderer->wrappers['control']['.odd'] = 'odd';
        $renderer->wrappers['label']['container'] = 'dt';
        $renderer->wrappers['label']['suffix'] = ':';
        $renderer->wrappers['control']['requiredsuffix'] = " \u{2022}";

        $form->addText('username', 'Имя:')
            ->setRequired('Пожалуйста, введите ваше имя.');

        $form->addPassword('password', 'Пароль:')
            ->setRequired('Пожалуйста, введите ваш пароль.');

        $form->addSubmit('send', 'Войти');

        $form->onSuccess[] = $this->signInFormSucceeded(...);

        return $form;
    }

    private function signInFormSucceeded(Form $form, \stdClass $data): void
    {
        try {
            $this->getUser()->login($data->username, $data->password);
            $this->redirect('Home:');
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
