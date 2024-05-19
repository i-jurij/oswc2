<?php

declare(strict_types=1);

namespace App\UI\Home;

use Nette;
use Nette\Utils\Finder;

/**
 * @property HomeTemplate $template
 */
final class HomePresenter extends Nette\Application\UI\Presenter
{
    public function renderDefault()
    {
        $this->template->pages = Finder::findDirectories('*')->in(APPDIR.'/UI/Home/Pages');
    }
}
class HomeTemplate extends Nette\Bridges\ApplicationLatte\Template
{
    public Finder $pages;
    public Nette\Security\User $user;
    public string $basePath;
    public string $baseUrl;
    public array $flashes;
    public object $presenter;
    public object $control;
}
