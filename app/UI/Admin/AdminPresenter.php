<?php

declare(strict_types=1);

namespace App\UI\Admin;

use App\UI\Accessory\CacheCleaner;
use App\UI\Accessory\RequireLoggedUser;
use Nette;
use Nette\Security\User;

/**
 * @property AdminTemplate $template
 */
final class AdminPresenter extends Nette\Application\UI\Presenter
{
    // Incorporates methods to check user login status
    use RequireLoggedUser;
    use CacheCleaner;

    public function renderDefault()
    {
        // $this->setLayout('layoutAdmin');
        $this->template->data = ['0' => ['alias' => 'admin_first_alias',
            'title' => 'admin first title',
            'decription' => 'admin first decription',
        ],
        ];
        // $this->redirect('Admin:Dashboard:');
    }
}

class AdminTemplate extends Nette\Bridges\ApplicationLatte\Template
{
    public User $user;
    public string $basePath;
    public string $baseUrl;
    public array $flashes;
    public object $presenter;
    public object $control;
    public array $sql;
    public array $data;
}
