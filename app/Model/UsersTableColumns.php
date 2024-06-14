<?php

declare(strict_types=1);

namespace App\Model;

class UsersTableColumns
{
    // Database table and column names
    public const TableName = 'users';
    protected const ColumnId = 'id';
    protected const ColumnName = 'username';
    protected const ColumnPasswordHash = 'password';
    protected const ColumnPhone = 'phone';
    protected const ColumnPhoneVerified = 'phone_verified';
    protected const ColumnEmail = 'email';
    protected const ColumnEmailVerified = 'email_verified';
    protected const ColumnAuthToken = 'auth_token';
    public const ColumnRole = 'role';
    protected const ColumnCreatedAt = 'created_at';
    protected const ColumnUpdatedAt = 'updated_at';
}
