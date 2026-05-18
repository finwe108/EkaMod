<?php

namespace Modules\Auth\Tests\Feature;

use Tests\TestCase;

/**
 * Tests authentication module routes.
 *
 * Module: Auth
 * Layer: Feature Test
 */
class AuthRoutesTest extends TestCase
{
    /**
     * Ensure login route preserves expected URL.
     *
     * @return void
     */
    public function test_login_route_name_generates_expected_url(): void
    {
        $this->assertEquals(
            url('/login'),
            route('login')
        );
    }

    /**
     * Ensure login store route preserves expected URL.
     *
     * @return void
     */
    public function test_login_store_route_name_generates_expected_url(): void
    {
        $this->assertEquals(
            url('/login'),
            route('login.store')
        );
    }

    /**
     * Ensure logout route preserves expected URL.
     *
     * @return void
     */
    public function test_logout_route_name_generates_expected_url(): void
    {
        $this->assertEquals(
            url('/logout'),
            route('logout')
        );
    }
}