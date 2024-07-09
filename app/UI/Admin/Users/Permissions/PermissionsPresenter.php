<?php

declare(strict_types=1);

namespace App\UI\Admin\Users\Permissions;

use App\Model\PermissionFacade;
use App\UI\Accessory\RequireLoggedUser;
use Nette;
use Nette\Application\UI\Form;

/**
 * @property PermissionsTemplate $template
 */
final class PermissionsPresenter extends Nette\Application\UI\Presenter
{
    // Incorporates methods to check user login status
    use RequireLoggedUser;

    public string $backlink;
    protected $user_data;

    public function __construct(
        private PermissionFacade $pf)
    {
    }

    private function get(int $id)
    {
    }

    public function actionAdd()
    {
        $actions = $this->pf->actionListFromModelDir();
        // $actions = $this->pf->presenterList() ?? null;
        $existed_permissions = $this->pf->list();
        if (is_array($actions) && !empty($actions)) {
            foreach ($actions as $resource => $values) {
                foreach ($values as $key => $action) {
                    if (isset($existed_permissions[$resource]) && \in_array($action, $existed_permissions[$resource])) {
                        unset($actions[$resource][$key]);
                    }
                }
            }
        }

        return $this->template->actions = $actions;
    }

    public function createComponentFormPermissionsAdd(): Form
    {
        $form = new Form();
        $form->addProtection();
        $form->setHtmlAttribute('id', 'formPermissionsAdd')
           ->setHtmlAttribute('class', 'form');

        $form->addSubmit('addPermissions', 'Add permissions');

        $form->onSuccess[] = [$this, 'add'];

        return $form;
    }

    public function add(Form $form): void
    {
        $data['resource'] = $form->getHttpData($form::DataText, 'resource');
        $data['action'] = $form->getHttpData($form::DataText, 'action[]');

        if (!empty($data['resource']) && !empty($data['action'])) {
            try {
                $this->pf->add($data);

                $this->flashMessage('Permission added', 'text-success');
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
            $this->redirect(':Admin:Users:Permissions:add');
        }

        $this->redirect(':Admin:');
    }

    public function actionDelete()
    {
        $existed_permissions = [];
        foreach ($this->pf->table as $row) {
            $existed_permissions[$row->resource][] = [
                'id' => $row->id,
                'action' => $row->action,
            ];
        }

        return $this->template->existed_permissions = $existed_permissions;
    }

    public function createComponentFormPermissionsDelete(): Form
    {
        $form = new Form();
        $form->addProtection();
        $form->setHtmlAttribute('id', 'formPermissionsDelete')
           ->setHtmlAttribute('class', 'form');

        $form->addSubmit('deletePermissions', 'Delete permissions');

        $form->onSuccess[] = [$this, 'delete'];

        return $form;
    }

    public function delete(Form $form): void
    {
        $data = $form->getHttpData($form::DataText, 'permission[]');
        try {
            $this->pf->delete($data);
            $this->flashMessage('Permissions was deleted', 'text-success');
        } catch (\Throwable $e) {
            $this->flashMessage('Caught Exception!'.PHP_EOL
                    .'Error message: '.$e->getMessage().PHP_EOL
                    .'File: '.$e->getFile().PHP_EOL
                    .'Line: '.$e->getLine().PHP_EOL
                    .'Error code: '.$e->getCode().PHP_EOL
                    .'Trace: '.$e->getTraceAsString().PHP_EOL, 'text-danger');
        }

        $this->redirect(':Admin:');
    }
}
