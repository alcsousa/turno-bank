<?php

namespace Tests\Unit\Providers;

use App\Http\Responses\Fortify\LoginResponse;
use App\Http\Responses\Fortify\LogoutResponse;
use App\Http\Responses\Fortify\RegisterResponse;
use App\Providers\FortifyServiceProvider;
use Tests\TestCase;

class FortifyServiceProviderTest extends TestCase
{
    public function test_it_registers_fortify_login_response_binding_correctly()
    {
        $provider = new FortifyServiceProvider($this->app);

        $provider->register();

        $this->assertInstanceOf(
            LoginResponse::class,
            $this->app->make(\Laravel\Fortify\Contracts\LoginResponse::class)
        );
    }

    public function test_it_registers_fortify_register_response_binding_correctly()
    {
        $provider = new FortifyServiceProvider($this->app);

        $provider->register();

        $this->assertInstanceOf(
            RegisterResponse::class,
            $this->app->make(\Laravel\Fortify\Contracts\RegisterResponse::class)
        );
    }

    public function test_it_registers_fortify_logout_response_binding_correctly()
    {
        $provider = new FortifyServiceProvider($this->app);

        $provider->register();

        $this->assertInstanceOf(
            LogoutResponse::class,
            $this->app->make(\Laravel\Fortify\Contracts\LogoutResponse::class)
        );
    }
}
