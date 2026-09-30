<?php

declare(strict_types=1);

namespace app\models;

use yii\base\BaseObject;
use yii\web\IdentityInterface;

class User extends BaseObject implements IdentityInterface
{
    public int|string $id = '';
    public string $username = '';
    public string $passwordHash = '';

    /**
     * Builds the single owner account from .env, or null if not configured.
     */
    private static function owner(): static|null
    {
        $username = (string) ($_ENV['APP_USERNAME'] ?? $_SERVER['APP_USERNAME'] ?? '');
        $hash = (string) ($_ENV['APP_PASSWORD_HASH'] ?? $_SERVER['APP_PASSWORD_HASH'] ?? '');

        if ($username === '' || $hash === '') {
            return null;
        }

        return new static(['id' => '1', 'username' => $username, 'passwordHash' => $hash]);
    }

    public static function findIdentity($id): static|null
    {
        $user = self::owner();

        return $user !== null && (string) $id === (string) $user->id ? $user : null;
    }

    public static function findIdentityByAccessToken($token, $type = null): static|null
    {
        return null;
    }

    public static function findByUsername(string $username): static|null
    {
        $user = self::owner();

        return $user !== null && strcasecmp($user->username, $username) === 0 ? $user : null;
    }

    public function getId(): int|string
    {
        return $this->id;
    }

    /**
     * Derived from the password hash, so changing your password
     * also logs out any old "remember me" cookies.
     */
    public function getAuthKey(): string|null
    {
        return hash('sha256', $this->passwordHash);
    }

    public function validateAuthKey($authKey): bool
    {
        return hash_equals($this->getAuthKey(), (string) $authKey);
    }
}