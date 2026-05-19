# Document Types Module

## Purpose

The Document Types module manages administrative CRUD operations for document type records.

This module is part of the gradual modular monolith migration of the existing Laravel school management system.

## Responsibilities

- List document types
- Create document types
- Edit document types
- Update document types
- Delete document types
- Preserve existing document type behavior

## Routes

The module currently registers routes inside the existing admin route group.

| Method | URL | Route Name | Purpose |
|---|---|---|---|
| GET | /admin/document-types | admin.document-types.index | List document types |
| GET | /admin/document-types/create | admin.document-types.create | Show create form |
| POST | /admin/document-types | admin.document-types.store | Store document type |
| GET | /admin/document-types/{document_type} | admin.document-types.show | Show document type |
| GET | /admin/document-types/{document_type}/edit | admin.document-types.edit | Show edit form |
| PUT/PATCH | /admin/document-types/{document_type} | admin.document-types.update | Update document type |
| DELETE | /admin/document-types/{document_type} | admin.document-types.destroy | Delete document type |

## Dependencies

- Existing Laravel authentication
- Existing role middleware: `super_admin`, `admin`, `hr`
- Existing `App\Models` model used by the original controller
- Existing Blade views
- Existing database schema

## Important Business Rules

Do not change document type validation, storage, deletion, authorization, route model binding, or view behavior during this phase.

The original controller must remain in `app/Http/Controllers/Admin` until the module is fully verified.

## Integration Points

- Admin sidebar/menu links
- Existing route names: `admin.document-types.*`
- Existing Blade views
- Existing RBAC middleware
- Document requirement rules
- Student document verification

## Migration Status

Current phase: copied controller and moved route ownership into the module.

Original files are intentionally preserved for rollback safety.