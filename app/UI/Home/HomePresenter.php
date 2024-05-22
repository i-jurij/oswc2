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
    public function __construct(private \App\Model\PagesListFacade $db_pages)
    {
    }

    public function renderDefault()
    {
        $db_data = $this->db_pages->getPagesData();
        if (count($db_data) > 0) {
            $this->template->pages_data = $db_data;
        }
    }
}
class HomeTemplate extends Nette\Bridges\ApplicationLatte\Template
{
    // public Finder $pages;
    public Nette\Security\User $user;
    public string $basePath;
    public string $baseUrl;
    public array $flashes;
    public object $presenter;
    public object $control;
    public array $pages_data;
}
