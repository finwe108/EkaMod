# Modular Monolith Migration Progress

## Completed

- Split route files
- Added Modules PSR-4 autoloading
- Migrated Announcements module
- Migrated School Settings module
- Migrated Document Types module
- Added module service providers
- Added module views
- Added services
- Added actions
- Added form requests
- Added basic feature tests

## Preserved

- Existing URLs
- Existing route names
- Existing middleware
- Existing RBAC
- Existing models in app/Models
- Existing database schema

## Known Follow-up

- Announcements `posted_by` currently references `teachers.id`.
- Admin-created announcements currently store `posted_by = null`.
- A future dedicated schema refactor may change announcement authorship to `users.id`.