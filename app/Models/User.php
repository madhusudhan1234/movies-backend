<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\DBTables;
use App\Enums\UserRole;
use Carbon\CarbonInterface;
use Database\Factories\UserFactory;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/**
 * @property int                  $id
 * @property string               $name
 * @property string               $email
 * @property CarbonInterface|null $email_verified_at
 * @property string               $password
 * @property UserRole             $role
 * @property string               $remember_token
 * @property CarbonInterface      $created_at
 * @property CarbonInterface      $updated_at
 */
class User extends Authenticatable implements MustVerifyEmail, \Illuminate\Contracts\Auth\CanResetPassword
{
    use HasApiTokens;
    use HasFactory;
    use Notifiable;
    use CanResetPassword;

    /** @use HasFactory<UserFactory> */
    protected $table = DBTables::USERS;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function favoriteMovies(): BelongsToMany
    {
        return $this->belongsToMany(Movie::class, 'favorites')->withTimestamps();
    }

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
            'role'              => UserRole::class,
        ];
    }
}
