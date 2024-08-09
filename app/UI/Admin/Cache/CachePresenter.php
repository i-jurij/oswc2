<?php

declare(strict_types=1);

namespace App\UI\Admin\Cache;

use App\UI\Accessory\CacheCleaner;
use App\UI\Accessory\ErrorFlashMessage;
use App\UI\Accessory\RequireLoggedUser;
use Nette;
use Nette\Security\User;
use Nette\Utils\FileSystem;

/**
 * @property CacheTemplate $template
 */
final class CachePresenter extends Nette\Application\UI\Presenter
{
    // Incorporates methods to check user login status
    use RequireLoggedUser;
    use CacheCleaner;
    use ErrorFlashMessage;

    public function renderDefault(): void
    {
        $this->template->data = $this->getFileList(CACHE_DIR);
    }

    #[Requires(methods: 'POST')]
    public function actionClear(): void
    {
        if (!$this->getUser()->isAllowed('Cache', 'clear')) {
            $this->error('Forbidden', 403);
        }
        try {
            $httpRequest = $this->getHttpRequest();
            $data = $httpRequest->getPost('clear_cache');
            $i = 0;
            if (!empty($data)) {
                foreach ($data as $file) {
                    FileSystem::delete($file);
                    ++$i;
                }
            }

            $this->flashMessage("Cache success remove. Remove {$i} files.");
        } catch (\Throwable $e) {
            $this->flashMessage($this->erMes($e), 'text-danger');
        }

        $this->redirect(':Admin:Cache:');
    }

    public function actionClearAll(): void
    {
        try {
            $count = $this->cleanCache(CACHE_DIR) ?? 0;
            $this->flashMessage("All cached files was removed. Remove {$count} files.");
        } catch (\Throwable $th) {
            $this->flashMessage($this->erMes($th), 'text-danger');
        }

        $this->redirect(':Admin:Cache:');
    }
}
