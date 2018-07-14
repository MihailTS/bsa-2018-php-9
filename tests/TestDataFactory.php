<?php

namespace Tests;

use App\Currency;
use App\User;

class TestDataFactory
{
    public static function createUser(): User
    {
        return factory(User::class)->create([
            'is_admin' => false
        ]);
    }

    public static function createCurrency(): Currency
    {
        return factory(Currency::class)->create();
    }

    public static function createAdminUser(): User
    {
        return factory(User::class)->create([
            'is_admin' => true
        ]);
    }
}