<?php

declare(strict_types=1);

namespace App\Model;

use App\UI\Accessory\RequireLoggedUser;
use Nette;
use Nette\Database\Table\Selection;
use Nette\Database\UniqueConstraintViolationException;
use Nette\Security\Passwords;
use Nette\Utils\Validators;

/**
 * Manages user-related operations such as authentication and adding new users.
 */
final class UserFacade extends UsersTableColumns
{
    use RequireLoggedUser;

    // Minimum password length requirement for users
    public const PasswordMinLength = 7;
    protected string $columns = self::ColumnId.','.
            self::ColumnName.','.
            self::ColumnImage.','.
            self::ColumnPhone.','.
            self::ColumnPhoneVerified.','.
            self::ColumnEmail.','.
            self::ColumnEmailVerified.','.
            self::ColumnRole.','.
            self::ColumnCreatedAt.','.
            self::ColumnUpdatedAt;
    protected Selection $table;

    // Dependency injection of database explorer and password utilities
    public function __construct(
        public Nette\Database\Explorer $sqlite,
        private Passwords $passwords,
    ) {
        $this->table = $this->sqlite->table(self::TableName);
    }

    public function getAllUsersData(): Selection
    {
        $users_data = $this->table->select($this->columns);

        return $users_data;
    }

    public function getUserData($id)
    {
        $user_data = $this->table->select($this->columns)->where('id', $id);

        return $user_data;
    }

    public function deleteUserData($id): void
    {
        try {
            $user_data = $this->table->where('id', $id)->delete();
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function countUsers()
    {
        return $count = count($this->table);
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
                self::ColumnAuthToken => $this->token(),
            ]);
        } catch (UniqueConstraintViolationException $e) {
            throw new DuplicateNameException();
        }
    }

    #[Requires(methods: 'POST', sameOrigin: true)]
    public function add($data): void
    {
        if (!empty($data->email)) {
            Validators::assert($data->email, 'email');
        }
        // $email = !empty($data->email) ? $data->email : $data->username.'@'.$data->username.'.com';
        $insert_array = [
                self::ColumnName => $data->username,
                self::ColumnPasswordHash => $this->passwords->hash($data->password),
                self::ColumnPhone => $data->phone ?? null,
                self::ColumnEmail => $data->email ?? null,
                self::ColumnRole => $data->role ?? null,
                self::ColumnAuthToken => $this->token(),
                // self::ColumnCreatedAt => $created_at,
                // self::ColumnUpdatedAt => $updated_at,
        ];
        $insert_array = array_filter($insert_array);
        try {
            $this->sqlite->table(self::TableName)->insert($insert_array);
            // email or sms to new user with auth_token for verification
            // $this->email->to()->text('form with links to verification cli or accessory);
        } catch (Exception $e) {
            throw new Exception();
        }
    }

    public function token()
    {
        return Nette\Utils\Random::generate(15);
    }
}

/**
 * Custom exception for duplicate usernames.
 */
class DuplicateNameException extends \Exception
{
}
