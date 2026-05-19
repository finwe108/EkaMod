# School Settings Module

## Purpose

The School Settings module manages administrative configuration for school-wide settings.

This module is part of the gradual modular monolith migration of the existing Laravel school management system.

## Responsibilities

- Display the school settings edit form
- Update school-wide configuration values
- Preserve existing behavior, permissions, views, and route names

## Routes

The module currently registers routes inside the existing admin route group.

| Method | URL | Route Name | Purpose |
|---|---|---|---|
| GET | /admin/school-settings | admin.school-settings.edit | Show school settings form |
| PUT | /admin/school-settings | admin.school-settings.update | Update school settings |

## Dependencies

- Existing Laravel authentication
- Existing role middleware: `super_admin`, `admin`, `hr`
- Existing `App\Models` model or config storage used by the current controller
- Existing Blade views
- Existing database schema

## Important Business Rules

Do not change how school settings are validated, stored, or displayed during this migration phase.

The original controller should remain in `app/Http/Controllers/Admin` until this module is fully verified.

## Integration Points

- Admin dashboard/sidebar links
- Existing route names: `admin.school-settings.*`
- Existing Blade views
- Existing RBAC middleware

## Migration Status

Current phase: copied controller and moved route ownership into the module.

Original files are intentionally preserved for rollback safety.