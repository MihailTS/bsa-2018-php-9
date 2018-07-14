<?php

namespace Tests\Browser\Task1;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use Tests\TestDataFactory;

class Task1AuthExistTest extends DuskTestCase
{
    use DatabaseMigrations;

    const USER_PASSWORD = 'secret';

    public function test_auth_controls()
    {
        $this->browse(
            function (Browser $browser) {
                $browser->visit('/')
                    ->assertSeeLink('Login')
                    ->assertSeeLink('Register')
                    ->clickLink('Login')
                    ->assertPathIs('/login')
                    ->back()
                    ->clickLink('Register')
                    ->assertPathIs('/register');
            }
        );
    }

    public function test_login()
    {
        $this->browse(
            function (Browser $browser) {
                $user = TestDataFactory::createUser();

                $browser->visit('/login')
                    ->type('email', $user->email)
                    ->type('password', self::USER_PASSWORD)
                    ->press('Login')
                    ->assertPathIs('/currencies')
                    ->assertDontSeeLink('Login');
            }
        );
    }

    public function test_registration()
    {
        $this->browse(
            function (Browser $browser) {
                $browser->visit('/register')
                    ->assertSee('Register')
                    ->type('name', 'Donald Trump')
                    ->type('email', 'user@example.com')
                    ->value('input[name=password]', self::USER_PASSWORD)
                    ->value('input[name=password_confirmation]', self::USER_PASSWORD)
                    ->press('Register')
                    ->assertPathIs('/currencies')
                    ->assertDontSeeLink('Register');
            }
        );

        $this->assertDatabaseHas('users', [
            'name' => 'Donald Trump',
            'email' => 'user@example.com',
        ]);
    }
}