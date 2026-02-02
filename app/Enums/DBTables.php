<?php

namespace App\Enums;

/**
 *
 */
enum DBTables
{
    public const USERS                  = 'users';
    public const PASSWORD_RESET_TOKENS  = 'password_reset_tokens';
    public const PERSONAL_ACCESS_TOKENS = 'personal_access_tokens';
    public const GENRES                 = 'genres';
    public const PEOPLES                = 'peoples';
    public const MOVIES                 = 'movies';
    public const MOVIES_GENRE           = 'movies_genre';
    public const MOVIES_CREDITS         = 'movies_credits';
    public const FAVORITES              = 'favorites';
    public const MEDIAS                 = 'medias';
}
