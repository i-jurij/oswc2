<?php

declare(strict_types=1);

namespace App\Model;

use App\UI\Accessory\RequireLoggedUser;
use Nette\Database\Explorer;
use Nette\Database\Table\Selection;
use Nette\Utils\Finder;
use Nette\Utils\Strings;

/**
 * Manages user-related operations such as authentication and adding new users.
 */
final class PermissionFacade
{
    use RequireLoggedUser;
    public Selection $table;

    public function __construct(public Explorer $sqlite)
    {
        $this->table = $this->sqlite->table('permission');
    }

    public function actionListFromModelDir()
    {
        foreach (Finder::findFiles('*Facade.php')->in(APPDIR.DIRECTORY_SEPARATOR.'Model') as $name => $file) {
            $model = \pathinfo($name, PATHINFO_FILENAME);
            $resource = Strings::before($model, 'Facade', 1);
            if (\class_exists('App\Model\\'.$model)) {
                foreach (\get_class_methods('App\Model\\'.$model) as $action) {
                    $actions[$resource][] = $action;
                }
            }
        }

        return $actions;
    }

    public function list()
    {
        $existed_permissions = [];
        foreach ($this->table as $data) {
            $existed_permissions[$data->resource][] = $data->action;
        }

        return $existed_permissions;
    }

    public function add($data): void
    {
        try {
            $insert = [];
            foreach ($data['action'] as $value) {
                $insert[] = [
                    'resource' => $data['resource'],
                    'action' => $value,
                ];
            }

            $this->table->insert($insert);
        } catch (Exception $e) {
            throw new Exception();
        }
    }

    public function delete($ids_array): void
    {
        try {
            $this->table->where('id', $ids_array)->delete();
        } catch (Exception $e) {
            throw new Exception();
        }
    }
}
