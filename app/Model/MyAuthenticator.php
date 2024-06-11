<?php

declare(strict_types=1);

namespace App\Model;

use Nette;
use Nette\Database\Explorer;
use Nette\Security\AuthenticationException;
use Nette\Security\Passwords;
use Nette\Security\SimpleIdentity;

/**
 * Manages user-related operations such as authentication and adding new users.
 */
final class MyAuthenticator extends UsersTableColumns implements Nette\Security\Authenticator
{
    // Dependency injection of database explorer and password utilities
    public function __construct(
        private Explorer $sqlite,
        private Passwords $passwords,
    ) {
    }

    /**
     * Authenticate a user based on provided credentials.
     * Throws an AuthenticationException if authentication fails.
     */
    public function authenticate(string $username, string $password): SimpleIdentity
    {
        // Fetch the user details from the database by username
        $row = $this->sqlite->table(self::TableName)
            ->where(self::ColumnName, $username)
            ->fetch();

        // Authentication checks
        if (!$row) {
            throw new AuthenticationException('The username is incorrect.', self::IdentityNotFound);
        } elseif (!$this->passwords->verify($password, $row[self::ColumnPasswordHash])) {
            throw new AuthenticationException('The password is incorrect.', self::InvalidCredential);
        } elseif ($this->passwords->needsRehash($row[self::ColumnPasswordHash])) {
            $row->update([
                self::ColumnPasswordHash => $this->passwords->hash($password),
            ]);
        }

        // Return user identity without the password hash
        $arr = $row->toArray();
        unset($arr[self::ColumnPasswordHash]);

        return new Nette\Security\SimpleIdentity($row[self::ColumnId], $row[self::ColumnRole], $arr);
    }
}
