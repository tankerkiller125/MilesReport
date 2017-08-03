<?php

namespace Tests\Browser;

use App\User;
use Faker\Factory;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class LoginTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * Test login without remember me.
     *
     * @return void
     */
    public function testBasicLogin()
    {
        $user = factory(User::class)->create([
            'email' => Factory::create()->email,
            'report_schedule' => 30,
        ]);
        $this->browse(function (Browser $browser) use ($user) {
            $browser->visit('/login')
                ->type('email', $user->email)
                ->type('password', 'secret')
                ->press('Login')
                ->assertPathIs('/')
                ->assertSee('Create');
        });
    }

    /**
     * Test login with remember me token.
     *
     * @return void
     */
    public function testLoginWithRememberMe()
    {
        $user = \factory(User::class)->create([
            'email' => Factory::create()->email,
            'report_schedule' => 30,
        ]);
        $this->browse(function (Browser $browser) use ($user) {
            $browser->visit('/login')
                ->type('email', $user->email)
                ->type('password', 'secret')
                ->check('remember')
                ->press('Login')
                ->assertPathIs('/')
                ->assertSee('Create')
                ->assertHasCookie(\Auth::getRecallerName());
        });
    }
}
