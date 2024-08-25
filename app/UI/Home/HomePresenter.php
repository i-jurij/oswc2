<?php

declare(strict_types=1);

namespace App\UI\Home;

use App\Model\PageFacade;
use Michelf\MarkdownExtra;
use Nette;
use Nette\Utils\Finder;
use Nette\Utils\Html;

/**
 * @property HomeTemplate $template
 */
final class HomePresenter extends \App\UI\BasePresenter
{
    // use \App\UI\Accessory\LinkFromFileSystem;

    public function __construct(private PageFacade $db_pages)
    {
    }

    public function renderDefault()
    {
        $db_data = $this->db_pages->getPagesData();
        if (count($db_data) > 0) {
            $this->template->pages_data = $db_data;
        }
        // $this->template->menuList = $this->linkFromFileSystem(__DIR__);
    }

    public function renderPolitic()
    {
        $path = APPDIR.'/../resources/politic.md';
        if (\is_readable($path)) {
            $my_text = \file_get_contents($path);
            $my_html = MarkdownExtra::defaultTransform($my_text);

            $this->template->pages_data = [Html::el('div')
                ->setAttribute('class', 'm1 p2 rounded shadow bgcontent ')
                ->appendAttribute('style', 'columns', 'auto 24em')
                ->appendAttribute('style', 'text-align', 'justify')
                ->addHtml($my_html)];
        }
        /*
        $file = Finder::findFiles('politic.html')->in($path); // ->collect();
        foreach ($file as $key => $value) {
            $this->template->pages_data[$key] = $value;
        }
        */
        // $this->setView($db_data);
    }
}
class HomeTemplate extends \App\UI\BaseTemplate
{
    public Nette\Security\User $user;
    public string $basePath;
    public string $baseUrl;
    public array $flashes;
    public object $presenter;
    public object $control;
    public array $pages_data;
    // public array $menuList;
}
