<?php

declare(strict_types=1);

namespace App\UI\Admin\Logs;

use Nette;
use Nette\Utils\Finder;

/**
 * @property LogsTemplate $template
 */
final class LogsPresenter extends Nette\Application\UI\Presenter
{
    use \App\UI\Accessory\RequireLoggedUser;
    use \App\UI\Accessory\HumanSize;
    use \App\UI\Accessory\ClearFile;

    private string $log_directory = APPDIR.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'log';

    public function renderDefault()
    {
        if ($this->getUser()->isAllowed('Logs', 'list')) {
            foreach (Finder::findFiles(['*.log'])->in($this->log_directory) as $log) {
                $size = $log->getSize();
                $message = '';
                if ($size > 1048576 * 10) {
                    $message = 'File is bigger then 10Mb. You can clear it before open (last ten entries will be left)';
                }
                $this->template->logs[] = [
                    'name' => $log->getFilename(),
                    'realpath' => $log->getRealPath(),
                    'modification_time' => $log->getMTime(),
                    'size' => $this->formatBytes($size, 2),
                    'message' => $message,
                ];
            }
        } else {
            $this->flashMessage('You don\'t have permission for this');
            $this->redirect(':Admin:');
        }
    }

    public function renderShow($name, int $page = 1)
    {
        if ($this->getUser()->isAllowed('Logs', 'show')) {
            $this->template->name = $name;

            if (\is_readable($this->log_directory.DIRECTORY_SEPARATOR.$name)) {
                $lines = array_reverse(file($this->log_directory.DIRECTORY_SEPARATOR.$name), true);

                $paginator = new Nette\Utils\Paginator();
                $paginator->setItemCount(count($lines));
                $paginator->setItemsPerPage(10);
                $paginator->setPage($page);

                $this->template->lines = array_slice($lines, $paginator->getOffset(), $paginator->getLength(), true);

                $this->template->paginator = $paginator;
            } else {
                $this->flashMessage('You don\'t have permission for this');
                $this->redirect(':Admin:Logs:');
            }
        } else {
            $this->flashMessage('The logs file is not readable or not exist');
            $this->redirect(':Admin:Logs:');
        }
    }

    public function renderClear($name)
    {
        if ($this->clearFile($this->log_directory.DIRECTORY_SEPARATOR.$name, 30)) {
            $this->flashMessage('Success! Log "'.$name.'" cleared');
            $this->redirectPermanent(':Admin:Logs:');
        } else {
            $this->flashMessage('Error! Log "'.$name.'" was NOT cleared.');
            $this->redirectPermanent(':Admin:Logs:');
        }
    }
}
/*
class UsersTemplate extends Nette\Bridges\ApplicationLatte\Template
{
    public Nette\Security\User $user;
    public string $basePath;
    public string $baseUrl;
    public array $flashes;
    public object $presenter;
    public object $control;
    public array $pages_data;
}
*/
