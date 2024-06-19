<?php

declare(strict_types=1);

namespace App\UI\Accessory;

use App\Model\UserFacade;
use Nette\Utils\Random;

class UserFact
{
    private array $array = [
        'username' => '',
        'password' => '',
        'phone' => '',
        'email' => '',
        'role' => '',
    ];
    private $ids;
    public $uf;

    public function __construct(UserFacade $userfacade)
    {
        $this->uf = $userfacade;
        $this->ids = $this->uf->sqlite->table('roles')->select('id')->fetchPairs('id');
    }

    public function seedUser()
    {
        $this->array['username'] = Random::generate(7, 'a-z');
        $this->array['password'] = password_hash(Random::generate(8, 'a-z0-9'), PASSWORD_DEFAULT);
        $this->array['phone'] = Random::generate(10, '0-9');
        $this->array['email'] = $this->array['username'].'@'.$this->array['username'].'.com';
        $id = array_rand($this->ids);
        $this->array['role'] = $id;

        return (object) $this->array;
    }

    public function saveUser($user)
    {
        $this->uf->add($user);
    }
}
