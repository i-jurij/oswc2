<?php

declare(strict_types=1);

namespace App\Model;

use Nette;
use Nette\Database\UniqueConstraintViolationException;
use Nette\Security\Passwords;
use Nette\Utils\Validators;

/**
 * Manages user-related operations such as authentication and adding new users.
 */
final class UserFacade extends UsersTableColumns
{
    // Minimum password length requirement for users
    public const PasswordMinLength = 7;

    // Dependency injection of database explorer and password utilities
    public function __construct(
        private Nette\Database\Explorer $sqlite,
        private Passwords $passwords,
    ) {
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
        } catch (UniqueConstraintViolationException $e) {
            throw new DuplicateNameException();
        }
    }

    public function add(string $username, string $password, string $role): void
    {
        Validators::assert($email, 'email');
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
        } catch (UniqueConstraintViolationException $e) {
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
