<?php

namespace Tests\Browser\Task3;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use Tests\TestDataFactory;

class Task3AdminActionsTest extends DuskTestCase
{
    use DatabaseMigrations;

    public function test_admin_see_controls_currency_list_page()
    {
        $this->browse(
            function (Browser $browser) {
                $admin = TestDataFactory::createAdminUser();

                TestDataFactory::createCurrency();

                $browser
                    ->loginAs($admin)
                    ->visit('/currencies')
                    ->assertSeeLink('Add')
                    ->assertSeeLink('Edit')
                    ->assertSee('Delete');
            }
        );
    }

    public function test_admin_see_controls_currency_item_page()
    {
        $this->browse(
            function (Browser $browser) {
                $admin = TestDataFactory::createAdminUser();

                TestDataFactory::createCurrency();

                $browser
                    ->loginAs($admin)
                    ->visit('/currencies/1')
                    ->assertSeeLink('Edit')
                    ->assertSee('Delete');
            }
        );
    }

    public function test_admin_can_visit_add_currency_page()
    {
        $this->browse(
            function (Browser $browser) {
                $admin = TestDataFactory::createAdminUser();

                $browser
                    ->loginAs($admin)
                    ->visit('/currencies/add')
                    ->assertPathIs('/currencies/add');
            }
        );
    }

    public function test_admin_can_visit_edit_currency_page()
    {
        $this->browse(
            function (Browser $browser) {
                $admin = TestDataFactory::createAdminUser();

                TestDataFactory::createCurrency();

                $browser
                    ->loginAs($admin)
                    ->visit('/currencies/1/edit')
                    ->assertPathIs('/currencies/1/edit');
            }
        );
    }

    public function test_admin_can_add_currency()
    {
        $this->browse(
            function (Browser $browser) {
                $admin = TestDataFactory::createAdminUser();

                $browser
                    ->loginAs($admin)
                    ->visit('/currencies/add')
                    ->value('input[name=title]', 'Coin')
                    ->value('input[name=short_name]', 'cn')
                    ->value('input[name=price]', '9999')
                    ->value('input[name=logo_url]', 'https://example.com')
                    ->press('Save')
                    ->assertPathIs('/currencies');
            }
        );

        $this->assertDatabaseHas('currency', [
            'id' => 1
        ]);
    }

    public function test_admin_can_update_currency()
    {
        $this->browse(
            function (Browser $browser) {
                $admin = TestDataFactory::createAdminUser();

                TestDataFactory::createCurrency();

                $browser
                    ->loginAs($admin)
                    ->visit('/currencies/1/edit')
                    ->assertSee('Save')
                    ->press('Save')
                    ->assertPathIs('/currencies/1');
            }
        );

        $this->assertDatabaseHas('currency', [
            'id' => 1
        ]);
    }

    public function test_admin_can_delete_currency()
    {
        $this->browse(
            function (Browser $browser) {
                $admin = TestDataFactory::createAdminUser();

                TestDataFactory::createCurrency();

                $browser
                    ->loginAs($admin)
                    ->visit('/currencies/1')
                    ->assertSee('Delete')
                    ->press('Delete')
                    ->assertPathIs('/currencies');
            }
        );

        $this->assertDatabaseMissing('currency', [
            'id' => 1
        ]);
    }
}