<?php

namespace Modules\Employees\Tests\Feature;

use Tests\TestCase;

/**
 * Tests employee module routes.
 *
 * Module: Employees
 * Layer: Feature Test
 */
class EmployeeRoutesTest extends TestCase
{
    public function test_employees_index_requires_authentication(): void
    {
        $response = $this->get(route('admin.employees.index'));

        $response->assertRedirect(route('login', ['expired' => 1]));
    }

    public function test_employees_route_name_generates_expected_url(): void
    {
        $this->assertEquals(
            url('/admin/employees'),
            route('admin.employees.index')
        );
    }
}