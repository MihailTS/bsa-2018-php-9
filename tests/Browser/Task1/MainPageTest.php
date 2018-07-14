<?php

namespace Tests\Browser\Task1;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;

class MainPageTest extends DuskTestCase
{
    use DatabaseMigrations;

    public function test_header_exists()
    {
        $this->browse(
            function (Browser $browser) {
                $browser->visit('/')
                    ->assertSee('Currency market');
            }
        );
    }
}
