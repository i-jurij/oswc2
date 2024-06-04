<?php

declare(strict_types=1);

namespace App\Model;

use Nette;
use Nette\Security\Passwords;

/**
 * Manages user-related operations such as authentication and adding new users.
 */
final class UserFacade implements Nette\Security\Authenticator
{
    // Minimum password length requirement for users
    public const PasswordMinLength = 7;

    // Database table and column names
    private const TableName = 'users';
    private const ColumnId = 'id';
    private const ColumnName = 'username';
    private const ColumnPasswordHash = 'password';
    private const ColumnPhone = 'phone';
    private const ColumnPhoneVerified = 'phone_verified';
    private const ColumnEmail = 'email';
    private const ColumnEmailVerified = 'email_verified';
    private const ColumnAuthToken = 'auth_token';
    private const ColumnRole = 'role';
    private const ColumnCreatedAt = 'created_at';
    private const ColumnUpdatedAt = 'updated_at';

    // Dependency injection of database explorer and password utilities
    public function __construct(
        private Nette\Database\Explorer $sqlite,
        private Passwords $passwords,
    ) {
    }

    /**
     * Authenticate a user based on provided credentials.
     * Throws an AuthenticationException if authentication fails.
     */
    public function authenticate(string $username, string $password): Nette\Security\SimpleIdentity
    {
        // Fetch the user details from the database by username
        $row = $this->sqlite->table(self::TableName)
            ->where(self::ColumnName, $username)
            ->fetch();

        // Authentication checks
        if (!$row) {
            throw new Nette\Security\AuthenticationException('The username is incorrect.', self::IdentityNotFound);
        } elseif (!$this->passwords->verify($password, $row[self::ColumnPasswordHash])) {
            throw new Nette\Security\AuthenticationException('The password is incorrect.', self::InvalidCredential);
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

    /**
     * Add a new user to the database.
     * Throws a DuplicateNameException if the username is already taken.
     */
    public function shortAdd(string $username, string $password, string $role): void
    {
        try {
            $this->sqlite->table(self::TableName)->insert([
                self::ColumnName => $username,
                self::ColumnPasswordHash => $this->passwords->hash($password),
                self::ColumnRole => $role,
            ]);
        } catch (Nette\Database\UniqueConstraintViolationException $e) {
            throw new DuplicateNameException();
        }
    }

    public function add(string $username, string $password, string $role): void
    {
        Nette\Utils\Validators::assert($email, 'email');
        // auth_token generated eg
        // $token = rand();
        try {
            $this->sqlite->table(self::TableName)->insert([
                self::ColumnName => $username,
                self::ColumnPasswordHash => $this->passwords->hash($password),
                self::ColumnPhone => $phone,
                self::ColumnEmail => $email,
                self::ColumnAuthToken => $token,
                self::ColumnRole => $role,
                // self::ColumnCreatedAt => $created_at,
                // self::ColumnUpdatedAt => $updated_at,
            ]);
        } catch (Nette\Database\UniqueConstraintViolationException $e) {
            throw new DuplicateNameException();
        }

        // email or sms to new user with auth_token for verification
        // $this->email->to()->text('form with links to verification cli or accessory);
    }
}

/**
 * Custom exception for duplicate usernames.
 */
class DuplicateNameException extends \Exception
{
}
