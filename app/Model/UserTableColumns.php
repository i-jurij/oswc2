<?php

declare(strict_types=1);

namespace App\Model;

class UserTableColumns
{
    // Database table and column names
    public const TableName = 'user';
    public const ColumnId = 'id';
    public const ColumnName = 'username';
    public const ColumnImage = 'image';
    public const ColumnPasswordHash = 'password';
    public const ColumnPhone = 'phone';
    public const ColumnPhoneVerified = 'phone_verified';
    public const ColumnEmail = 'email';
    public const ColumnEmailVerified = 'email_verified';
    public const ColumnAuthToken = 'auth_token';
    public const ColumnCreatedAt = 'created_at';
    public const ColumnUpdatedAt = 'updated_at';

    public static function getColumns(): string
    {
        $ref = new \ReflectionClass(__CLASS__);
        $column_array = $ref->getConstants();
        unset($column_array['TableName']);

        return \implode(', ', $column_array);
    }
}
