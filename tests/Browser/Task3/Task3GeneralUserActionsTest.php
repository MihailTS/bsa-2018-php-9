<?php

namespace Tests\Browser\Task3;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use Tests\TestDataFactory;

class Task3GeneralUserActionsTest extends DuskTestCase
{
    use DatabaseMigrations;

    public function test_user_can_see_currency_list_page()
    {
        $this->browse(
            function (Browser $browser) {
                $user = TestDataFactory::createUser();

                $browser
                    ->loginAs($user)
                    ->visit('/currencies')
                    ->assertPathIs('/currencies');
            }
        );
    }

    public function test_user_can_see_currency_item_page()
    {
        $this->browse(
            function (Browser $browser) {
                $user = TestDataFactory::createUser();

                TestDataFactory::createCurrency();

                $browser
                    ->loginAs($user)
                    ->visit('/currencies/1')
                    ->assertPathIs('/currencies/1');
            }
        );
    }

    public function test_user_dont_see_admin_controls_currency_list_page()
    {
        $this->browse(
            function (Browser $browser) {
                $user = TestDataFactory::createUser();

                $browser->loginAs($user)
                    ->visit('/currencies')
                    ->assertDontSeeLink('Add')
                    ->assertDontSeeLink('Edit')
                    ->assertDontSee('Delete');
            }
        );
    }

    public function test_user_dont_see_admin_controls_currency_item_page()
    {
        $this->browse(
            function (Browser $browser) {
                $user = TestDataFactory::createUser();

                TestDataFactory::createCurrency();

                $browser->loginAs($user)
                    ->visit('/currencies/1')
                    ->assertDontSeeLink('Add')
                    ->assertDontSeeLink('Edit')
                    ->assertDontSee('Delete');
            }
        );
    }

    public function test_user_dont_see_create_currency_page()
    {
        $this->browse(
            function (Browser $browser) {
                $user = TestDataFactory::createUser();

                $browser->loginAs($user)
                    ->visit('/currencies/add')
                    ->assertPathIs('/');
            }
        );
    }

    public function test_user_dont_see_edit_currency_page()
    {
        $this->browse(
            function (Browser $browser) {
                $user = TestDataFactory::createUser();

                $browser
                    ->loginAs($user)
                    ->visit('/currencies/1/edit')
                    ->assertPathIs('/');
            }
        );
    }

    public function test_user_cant_add_currency()
    {
        $user = TestDataFactory::createUser();

        $this->actingAs($user)
            ->withSession([
                'X-CSRF-TOKEN' => csrf_token()
            ])
            ->post('/currencies', [
                '_token' => csrf_token(),
                'title' => 'coin',
                'short_name' => 'cn',
                'logo_url' => 'https://example.com',
                'price' => 10
            ])
            ->assertRedirect('/');

        $this->assertDatabaseMissing('currency', [
            'id' => 1
        ]);
    }

    public function test_user_cant_edit_currency()
    {
        $user = TestDataFactory::createUser();

        $currency = TestDataFactory::createCurrency();

        $this->actingAs($user)
            ->withSession([
                'X-CSRF-TOKEN' => csrf_token()
            ])
            ->put('/currencies/1', [
                '_token' => csrf_token(),
                'title' => 'coin',
                'short_name' => 'cn',
                'logo_url' => 'https://example.com',
                'price' => 10
            ])
            ->assertRedirect('/');

        $this->assertDatabaseHas('currency', [
            'id' => 1,
            'title' => $currency->title,
            'short_name' => $currency->short_name,
            'logo_url' => $currency->logo_url,
            'price' => $currency->price
        ]);
    }

    public function test_user_cant_delete_currency()
    {
        $user = TestDataFactory::createUser();

        TestDataFactory::createCurrency();

        $this->actingAs($user)
            ->withSession([
                'X-CSRF-TOKEN' => csrf_token()
            ])
            ->delete('/currencies/1', [
                '_token' => csrf_token(),
            ])
            ->assertRedirect('/');

        $this->assertDatabaseHas('currency', [
            'id' => 1
        ]);
    }
}