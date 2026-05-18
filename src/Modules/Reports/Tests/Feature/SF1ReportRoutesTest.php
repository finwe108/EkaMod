<?php

namespace Modules\Reports\Tests\Feature;

use Tests\TestCase;

/**
 * Tests SF1 report routes.
 *
 * Module: Reports
 * Layer: Feature Test
 */
class SF1ReportRoutesTest extends TestCase
{
    /**
     * Ensure SF1 filter page requires authentication.
     *
     * @return void
     */
    public function test_sf1_filter_requires_authentication(): void
    {
        $response = $this->get(route('admin.reports.sf1.filter'));

        $response->assertRedirect(route('login', ['expired' => 1]));
    }

    /**
     * Ensure SF1 filter route preserves expected URL.
     *
     * @return void
     */
    public function test_sf1_filter_route_name_generates_expected_url(): void
    {
        $this->assertEquals(
            url('/admin/reports/sf1'),
            route('admin.reports.sf1.filter')
        );
    }

    /**
     * Ensure SF1 print route preserves expected URL.
     *
     * @return void
     */
    public function test_sf1_print_route_name_generates_expected_url(): void
    {
        $this->assertEquals(
            url('/admin/reports/sf1/print'),
            route('admin.reports.sf1.print')
        );
    }

    /**
     * Ensure SF1 generated route preserves expected URL.
     *
     * @return void
     */
    public function test_sf1_generated_route_name_generates_expected_url(): void
    {
        $this->assertEquals(
            url('/admin/reports/sf1/generated'),
            route('admin.reports.sf1.generated')
        );
    }
}