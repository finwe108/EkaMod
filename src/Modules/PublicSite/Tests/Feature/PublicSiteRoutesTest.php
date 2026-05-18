<?php

namespace Modules\PublicSite\Tests\Feature;

use Tests\TestCase;

/**
 * Tests public site routes.
 *
 * Module: PublicSite
 * Layer: Feature Test
 */
class PublicSiteRoutesTest extends TestCase
{
    /**
     * Ensure public home route preserves expected URL.
     *
     * @return void
     */
    public function test_public_home_route_name_generates_expected_url(): void
    {
        $this->assertEquals(
            url('/'),
            route('public.home')
        );
    }

    /**
     * Ensure about route preserves expected URL.
     *
     * @return void
     */
    public function test_public_about_route_name_generates_expected_url(): void
    {
        $this->assertEquals(
            url('/about'),
            route('public.about')
        );
    }

    /**
     * Ensure privacy route preserves expected URL.
     *
     * @return void
     */
    public function test_public_privacy_route_name_generates_expected_url(): void
    {
        $this->assertEquals(
            url('/privacy'),
            route('public.privacy')
        );
    }
}