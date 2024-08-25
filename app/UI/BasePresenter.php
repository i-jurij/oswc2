<?php

declare(strict_types=1);

namespace App\UI;

abstract class BasePresenter extends \Nette\Application\UI\Presenter
{
    use Accessory\Breadcrumb;

    public function beforeRender()
    {
        $this->template->breadcrumb = $this->getBC();
        $this->template->shared_templates = APPDIR.DIRECTORY_SEPARATOR.'UI'.DIRECTORY_SEPARATOR.'shared_templates'.DIRECTORY_SEPARATOR;
    }
}

class BaseTemplate extends \Nette\Bridges\ApplicationLatte\Template
{
    public array $breadcrumb;
    public string $shared_templates;
}
