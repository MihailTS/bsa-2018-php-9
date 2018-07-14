<?php

namespace Tests\Browser\Task1;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use Tests\TestDataFactory;

class Task1AuthorizationTest extends DuskTestCase
{
    use DatabaseMigrations;

    public function test_unauthorized_dont_see_pages()
    {
        $this->browse(
            function (Browser $browser) {
                $browser->visit('/currencies')
                    ->assertPathIs('/login');

                $browser->visit('/currencies/1')
                    ->assertPathIs('/login');

                $browser->visit('/currencies/1/edit')
                    ->assertPathIs('/login');

                $browser->visit('/currencies/add')
                    ->assertPathIs('/login');
            }
        );
    }

    public function test_authorized_see_pages()
    {
        $this->browse(
            function (Browser $browser) {
                $user = TestDataFactory::createUser();

                TestDataFactory::createCurrency();

                $browser->loginAs($user)
                    ->visit('/currencies')
                    ->assertPathIs('/currencies')
                    ->visit('/currencies/1')
                    ->assertPathIs('/currencies/1');
            }
        );
    }
}