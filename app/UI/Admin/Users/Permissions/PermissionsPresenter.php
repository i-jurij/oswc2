<?php

declare(strict_types=1);

namespace App\UI\Admin\Users\Permissions;

use App\UI\Accessory\FormFactory;
use App\UI\Accessory\RequireLoggedUser;
use Nette;
use Nette\Application\UI\Form;
use Nette\Utils\Finder;
use Nette\Utils\Strings;

/**
 * @property UsersTemplate $template
 */
final class PermissionsPresenter extends Nette\Application\UI\Presenter
{
    // Incorporates methods to check user login status
    use RequireLoggedUser;

    public string $backlink;
    protected $user_data;

    public function __construct(private FormFactory $formFactory)
    {
    }

    public function createComponentFormPermissionsAdd(): Form
    {
        $form = $this->formFactory->create();
        $form->setHtmlAttribute('id', 'formPermissionsAdd')
           ->setHtmlAttribute('class', 'form');
        /*
        $form->addText('resource', 'Resources name:')
            ->setHtmlAttribute('placeholder', 'Resources name:')
            ->setRequired()
            ->addRule($form::Pattern, 'Resources name = models name', '^[a-zA-Z0-9]$');

        $form->addText('action', 'Action name:')
            ->setHtmlAttribute('placeholder', 'Action name:')
            ->setRequired()
            ->addRule($form::Pattern, 'Action name = name of models method', '^[a-zA-Z0-9]$');
        */
        foreach (Finder::findFiles('*Facade.php')->in(APPDIR.DIRECTORY_SEPARATOR.'Model') as $name => $file) {
            $model = \pathinfo($name, PATHINFO_FILENAME);
            $resource = Strings::before($model, 'Facade', 1);
            if (\class_exists('App\Model\\'.$model)) {
                foreach (\get_class_methods('App\Model\\'.$model) as $action) {
                    $actions[$resource][] = $action;
                }
            }
            $resources[] = $resource;
        }

        $form->addRadioList('resource', 'Resource:', $resources);
        $form->addCheckboxList('action', 'Actions:', $actions);

        $form->addSubmit('send', 'Add permissions');

        $form->onSuccess[] = [$this, 'add'];

        return $form;
    }

    public function add(): void
    {
    }

    public function delete(): void
    {
    }
}
