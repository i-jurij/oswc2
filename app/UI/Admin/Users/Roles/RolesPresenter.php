<?php

declare(strict_types=1);

namespace App\UI\Admin\Users\Roles;

use App\Model\PermissionFacade;
use App\Model\RoleFacade;
use App\UI\Accessory\FormFactory;
use App\UI\Accessory\RequireLoggedUser;
use Nette;
use Nette\Application\UI\Form;

/**
 * @property UsersTemplate $template
 */
final class RolesPresenter extends Nette\Application\UI\Presenter
{
    // Incorporates methods to check user login status
    use RequireLoggedUser;

    public string $backlink;
    protected $user_data;

    public function __construct(
        private FormFactory $formFactory,
        private PermissionFacade $pf,
        private RoleFacade $rf
    ) {
    }

    public function createComponentFormRoleAdd(): Form
    {
        $form = $this->formFactory->create();
        $form->setHtmlAttribute('id', 'formRoleAdd')
           ->setHtmlAttribute('class', 'form');

        $form->addText('rolename', 'Role name:')
            ->setHtmlAttribute('placeholder', 'Role name:')
            ->setRequired()
            ->addRule($form::Pattern, 'Role name only letters in the number from 3 to 25', '^[a-zA-Z]{3,25}$')
            ->setMaxLength(25);

        $permissions = $this->pf->table->fetchAll();
        array_multisort(array_column($permissions, 'resource'), SORT_DESC, $permissions);
        $form->addCheckboxList('permissions', 'Permissions:', $permissions);

        $form->addSubmit('addRole', 'Add role');

        $form->onSuccess[] = [$this, 'add'];

        return $form;
    }

    public function add(Form $form, array $data): void
    {
        if (!empty($data['rolename'])) {
            try {
                $role = $this->rf->add($data);
                $this->flashMessage('Role "'.$role.'" was added', 'text-success');
            } catch (\Throwable $e) {
                $this->flashMessage('Caught Exception!'.PHP_EOL
                    .'Error message: '.$e->getMessage().PHP_EOL
                    .'File: '.$e->getFile().PHP_EOL
                    .'Line: '.$e->getLine().PHP_EOL
                    .'Error code: '.$e->getCode().PHP_EOL
                    .'Trace: '.$e->getTraceAsString().PHP_EOL, 'text-danger');
            }
        } else {
            $this->flashMessage('An empty form was received');
            $this->redirect(':Admin:Users:Roles:add');
        }
        $this->redirect(':Admin:');
    }

    public function renderDelete(): void
    {
    }

    public function delete(): void
    {
    }

    public function renderPermissions(): void
    {
    }

    public function update(): void
    {
    }
}
