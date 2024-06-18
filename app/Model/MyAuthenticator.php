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
        private UsersTableColumns $u_T_C
    ) {
    }

    /**
     * Authenticate a user based on provided credentials.
     * Throws an AuthenticationException if authentication fails.
     */
    public function authenticate(string $username, string $password): SimpleIdentity
    {
        // Fetch the user details from the database by username
        $user = $this->sqlite->table($this->u_T_C::TableName)
            ->where($this->u_T_C::ColumnName, $username)
            ->fetch();

        // Authentication checks
        if (!$user) {
            throw new AuthenticationException('The username is incorrect.', $this->u_T_C::IdentityNotFound);
        } elseif (!$this->passwords->verify($password, $user[$this->u_T_C::ColumnPasswordHash])) {
            throw new AuthenticationException('The password is incorrect.', self::InvalidCredential);
        } elseif ($this->passwords->needsRehash($user[$this->u_T_C::ColumnPasswordHash])) {
            $user->update([
                $this->u_T_C::ColumnPasswordHash => $this->passwords->hash($password),
            ]);
        }

        // Return user identity without the password hash
        $arr = $user->toArray();
        unset($arr[$this->u_T_C::ColumnPasswordHash]);

        // $role =
        return new SimpleIdentity($user[$this->u_T_C::ColumnId], $user[$this->u_T_C::ColumnRoleId], $arr);
        // return new Nette\Security\SimpleIdentity($row[$this->u_T_C::ColumnId], $row[$this->u_T_C::ColumnRole], $arr);
    }

    public function sleepIdentity(IIdentity $identity): SimpleIdentity
    {
        // мы возвращаем идентификатор прокси, где в качестве идентификатора выступает auth_token
        return new SimpleIdentity($identity->{$this->u_T_C::ColumnAuthToken});
    }

    public function wakeupIdentity(IIdentity $identity): ?SimpleIdentity
    {
        // заменить идентификатор прокси на полный идентификатор, как в authenticate()
        $row = $this->sqlite->table($this->u_T_C::TableName)
            ->where($this->u_T_C::ColumnAuthToken, $identity->getId())
            ->fetch();
        if (!empty($row)) {
            $arr = $row->toArray();
            unset($arr[$this->u_T_C::ColumnPasswordHash]);
        }

        return $row
            ? new SimpleIdentity($row[$this->u_T_C::ColumnId], $row[$this->u_T_C::ColumnRoleId], $arr)
            : null;
    }
}
