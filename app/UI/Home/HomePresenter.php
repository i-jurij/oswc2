<?php

declare(strict_types=1);

namespace App\UI\Home;

use Nette;
use Nette\Utils\Finder;

final class HomePresenter extends Nette\Application\UI\Presenter
{
    public $pages;

    public function renderDefault()
    {
        $this->template->pages = Finder::findDirectories('*')->in(APPDIR.'/UI/Home/Pages');
    }
}
