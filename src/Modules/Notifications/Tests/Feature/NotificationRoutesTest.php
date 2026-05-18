<?php

namespace Modules\Notifications\Tests\Feature;

use Tests\TestCase;

/**
 * Tests notification routes.
 *
 * Module: Notifications
 * Layer: Feature Test
 */
class NotificationRoutesTest extends TestCase
{
    /**
     * Ensure notifications index requires authentication.
     *
     * @return void
     */
    public function test_notifications_index_requires_authentication(): void
    {
        $response = $this->get(route('notifications.index'));

        $response->assertRedirect(route('login', ['expired' => 1]));
    }

    /**
     * Ensure notifications route name preserves expected URL.
     *
     * @return void
     */
    public function test_notifications_route_name_generates_expected_url(): void
    {
        $this->assertEquals(
            url('/notifications'),
            route('notifications.index')
        );
    }
}