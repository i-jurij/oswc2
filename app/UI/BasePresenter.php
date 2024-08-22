<?php

declare(strict_types=1);

namespace App\UI;

abstract class BasePresenter extends \Nette\Application\UI\Presenter
{
    use Accessory\Breadcrumb;

    public function beforeRender()
    {
        $this->template->breadcrumb = $this->getBC();
    }
}

class BaseTemplate extends \Nette\Bridges\ApplicationLatte\Template
{
    public array $breadcrumb;
}
