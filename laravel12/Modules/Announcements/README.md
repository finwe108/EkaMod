# Announcements Module

## Purpose

The Announcements module manages school announcement CRUD operations inside the School Enterprise System.

This module is part of the gradual modular monolith migration.

## Responsibilities

- List announcements
- Create announcements
- Edit announcements
- Update announcements
- Delete announcements
- Preserve existing admin announcement behavior

## Routes

The module currently registers routes inside the existing admin route group.

| Method | URL | Route Name | Purpose |
|--------|-----|------------|---------|
| GET | /admin/announcements | admin.announcements.index | List announcements |
| GET | /admin/announcements/create | admin.announcements.create | Show create form |
| POST | /admin/announcements | admin.announcements.store | Store announcement |
| GET | /admin/announcements/{announcement} | admin.announcements.show | Show announcement |
| GET | /admin/announcements/{announcement}/edit | admin.announcements.edit | Show edit form |
| PUT/PATCH | /admin/announcements/{announcement} | admin.announcements.update | Update announcement |
| DELETE | /admin/announcements/{announcement} | admin.announcements.destroy | Delete announcement |

## Dependencies

- Existing Laravel authentication
- Existing role middleware: `super_admin`, `admin`, `hr`
- Existing announcement model under `App\Models`
- Existing Blade views
- Existing database schema

## Migration Notes

The original controller in `app/Http/Controllers/Admin` must not be deleted yet.

During this phase, only route ownership is moved to the module controller copy.

## Important Business Rules

Do not change announcement creation, update, deletion, authorization, or validation behavior during this phase.

## Integration Points

- Admin dashboard/sidebar links
- Existing route names: `admin.announcements.*`
- Existing Blade views
- Existing RBAC middleware