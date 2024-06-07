<?php

declare(strict_types=1);

namespace App\UI\Admin\Logs;

use App\UI\Accessory\RequireLoggedUser;
use Nette;

/**
 * @property LogsTemplate $template
 */
final class LogsPresenter extends Nette\Application\UI\Presenter
{
    // Incorporates methods to check user login status
    use RequireLoggedUser;

    public function __construct()
    {
    }

    public function renderDefault()
    {
        // $this->setLayout('../@layout');
        if ($this->getUser()->isInRole('admin') || $this->getUser()->isInRole('moder')) { // is the admin role assigned to the user?
            $this->template->pages_data = ['0' => ['alias' => 'logs',
                                          'title' => 'Logs watching and clearing',
                                          'decription' => 'logs editing',
                                      ],
            ];
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
