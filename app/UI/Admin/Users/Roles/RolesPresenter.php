<?php

declare(strict_types=1);

namespace App\UI\Admin\Users\Roles;

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

    public function __construct(private FormFactory $formFactory)
    {
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

        $permissions_array = [];
        $form->addCheckboxList('permissions', 'Permissions:', $permissions_array);

        $form->addSubmit('send', 'Add role');

        $form->onSuccess[] = [$this, 'add'];

        return $form;
    }

    public function add(): void
    {
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
