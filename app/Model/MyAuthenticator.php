<?php

declare(strict_types=1);

namespace App\Model;

use Nette;
use Nette\Database\Explorer;
use Nette\Security\AuthenticationException;
use Nette\Security\IIdentity;
use Nette\Security\Passwords;
use Nette\Security\SimpleIdentity;

/**
 * Manages user-related operations such as authentication and adding new users.
 */
final class MyAuthenticator implements Nette\Security\Authenticator, Nette\Security\IdentityHandler
{
    // Dependency injection of database explorer and password utilities
    public function __construct(
        private Explorer $sqlite,
        private Passwords $passwords,
        private UsersTableColumns $uTC
    ) {
    }

    /**
     * Authenticate a user based on provided credentials.
     * Throws an AuthenticationException if authentication fails.
     */
    public function authenticate(string $username, string $password): SimpleIdentity
    {
        // Fetch the user details from the database by username
        $user = $this->sqlite->table($this->uTC::TableName)
            ->where($this->uTC::ColumnName, $username)
            ->fetch();

        // Authentication checks
        if (!$user) {
            throw new AuthenticationException('The username is incorrect.', $this->uTC::IdentityNotFound);
        } elseif (!$this->passwords->verify($password, $user[$this->uTC::ColumnPasswordHash])) {
            throw new AuthenticationException('The password is incorrect.', self::InvalidCredential);
        } elseif ($this->passwords->needsRehash($user[$this->uTC::ColumnPasswordHash])) {
            $user->update([
                $this->uTC::ColumnPasswordHash => $this->passwords->hash($password),
            ]);
        }
        $role = $user->ref('roles', 'role_id'); // sql query to table roles because role_name is need in identity
        // Return user identity without the password hash
        $arr = $user->toArray();
        $arr['role_id'] = $role->role_name;
        unset($arr[$this->uTC::ColumnPasswordHash]);

        return new SimpleIdentity($user[$this->uTC::ColumnId], $role->role_name, $arr);
        // return new Nette\Security\SimpleIdentity($row[$this->uTC::ColumnId], $row[$this->uTC::ColumnRole], $arr);
    }

    public function sleepIdentity(IIdentity $identity): SimpleIdentity
    {
        // мы возвращаем идентификатор прокси, где в качестве идентификатора выступает auth_token
        return new SimpleIdentity($identity->{$this->uTC::ColumnAuthToken});
    }

    public function wakeupIdentity(IIdentity $identity): ?SimpleIdentity
    {
        // заменить идентификатор прокси на полный идентификатор, как в authenticate()
        $row = $this->sqlite->table($this->uTC::TableName)
            ->where($this->uTC::ColumnAuthToken, $identity->getId())
            ->fetch();

        if (!empty($row)) {
            $role = $row->ref('roles', 'role_id');
            $arr = $row->toArray();
            $arr['role_id'] = $role->role_name;
            unset($arr[$this->uTC::ColumnPasswordHash]);
        }

        return $row
            ? new SimpleIdentity($row[$this->uTC::ColumnId], $role->role_name, $arr)
            : null;
    }
}
