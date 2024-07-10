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

    #[Requires(methods: 'POST')]
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

    public function createComponentFormRoleDelete(): Form
    {
        $form = $this->formFactory->create();
        $form->setHtmlAttribute('id', 'formRoleDelete')
           ->setHtmlAttribute('class', 'form');

        $roles = $this->rf->role->fetchAll();
        $form->addCheckboxList('role', 'Roles:', $roles);

        $form->addSubmit('deleteRole', 'Delete');

        $form->onSuccess[] = [$this, 'delete'];

        return $form;
    }

    #[Requires(methods: 'POST')]
    public function delete(Form $form, array $data): void
    {
        if (!empty($data['role'])) {
            try {
                // $role = $this->rf->delete($data);
                $message = '';
                $type = 'text-info';

                switch ($this->rf->delete($data)) {
                    case 0:
                        $message = 'Roles NOT deleted';
                        break;
                    case 1:
                        $message = 'Role(s) was deleted. Permissions NOT deleted (or role(s) have not been permissions';
                        break;
                    case 2:
                        $message = 'Role(s) was deleted. Permissions was deleted. Users roles NOT deleted (or users have not been assigned these roles)';
                        break;
                    case 3:
                        $message = 'Role(s) was deleted';
                        $type = 'text-success';
                        break;
                }

                $this->flashMessage($message, $type);
            } catch (\Throwable $e) {
                $this->flashMessage($e->getMessage().PHP_EOL
                    .'Trace: '.$e->getTraceAsString().PHP_EOL, 'text-danger');
            }
        } else {
            $this->flashMessage('An empty form was received');
            $this->redirect(':Admin:Users:Roles:delete');
        }
        $this->redirect(':Admin:');
    }

    public function renderPermissions(): void
    {
    }

    #[Requires(methods: 'POST')]
    public function update(): void
    {
    }
}
