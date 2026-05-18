<?php

namespace Modules\Announcements\Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Tests announcement module routes.
 *
 * Module: Announcements
 * Layer: Feature Test
 */
class AnnouncementRoutesTest extends TestCase
{
    /**
     * Ensure the announcements index route exists and is protected.
     *
     * @return void
     */
    public function test_announcements_index_requires_authentication(): void
    {
        $response = $this->get(route('admin.announcements.index'));

        $response->assertRedirect(route('login', ['expired' => 1]));
    }

    public function test_announcements_route_name_generates_expected_url(): void
    {
        $this->assertEquals(
            url('/admin/announcements'),
            route('admin.announcements.index')
        );
    }
}