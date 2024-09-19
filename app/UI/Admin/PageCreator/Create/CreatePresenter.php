<?php

declare(strict_types=1);

namespace App\UI\Admin\PageCreator\Create;

use Nette\Application\UI\Form;
use Nette\Security\User;

final class CreatePresenter extends \App\UI\BasePresenter
{
    // Incorporates methods to check user login status
    use \App\UI\Accessory\RequireLoggedUser;
    use \App\UI\Accessory\LinkFromFileSystem;

    public function renderDefault()
    {
        if (!$this->getUser()->isAllowed('Page', 'Create')) {
            $this->error('Forbidden', 403);
        }
        // for linkfromfilesystem
        $home_dir = APPDIR.DIRECTORY_SEPARATOR.'UI'.DIRECTORY_SEPARATOR.'Home'.DIRECTORY_SEPARATOR.'Pages';
        $this->template->homeTree = $this->linkFromFileSystem($home_dir);

        $admin_dir = APPDIR.DIRECTORY_SEPARATOR.'UI'.DIRECTORY_SEPARATOR.'Admin'.DIRECTORY_SEPARATOR.'Pages';
        $this->template->adminTree = $this->linkFromFileSystem($admin_dir);
    }

    public function createComponentFormPageCreatorCreate(): Form
    {
        $form = new Form();
        $form->addProtection();
        $form->setHtmlAttribute('id', 'formPageCreatorCreate')
           ->setHtmlAttribute('class', 'form');

        $form->addSubmit('PageCreatorCreate', 'Create');

        $form->onSuccess[] = [$this, 'create'];

        return $form;
    }

    #[Requires(methods: 'POST')]
    public function create(Form $form): void
    {
        if (!$this->getUser()->isAllowed('Page', 'Create')) {
            $this->error('Forbidden', 403);
        }

        $this->flashMessage(json_encode($_POST).'Page added', 'text-success');
        $this->redirect(':Admin:Cms:PageCreator:');
    }
}
