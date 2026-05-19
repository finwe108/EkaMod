# Document Requirement Rules Module

## Purpose

The Document Requirement Rules module manages which student documents are required for specific admission, enrollment, grade level, or student type scenarios.

This module is part of the gradual modular monolith migration.

## Responsibilities

- List document requirement rules
- Create document requirement rules
- Edit document requirement rules
- Update document requirement rules
- Delete document requirement rules
- Preserve existing document requirement rule behavior

## Routes

The module registers routes inside the existing admin route group.

| Method | URL | Route Name | Purpose |
|---|---|---|---|
| GET | /admin/document-requirement-rules | admin.document-requirement-rules.index | List rules |
| GET | /admin/document-requirement-rules/create | admin.document-requirement-rules.create | Show create form |
| POST | /admin/document-requirement-rules | admin.document-requirement-rules.store | Store rule |
| GET | /admin/document-requirement-rules/{document_requirement_rule}/edit | admin.document-requirement-rules.edit | Show edit form |
| PUT/PATCH | /admin/document-requirement-rules/{document_requirement_rule} | admin.document-requirement-rules.update | Update rule |
| DELETE | /admin/document-requirement-rules/{document_requirement_rule} | admin.document-requirement-rules.destroy | Delete rule |

## Dependencies

- Existing Laravel authentication
- Existing role middleware: `super_admin`, `admin`, `hr`
- Existing `App\Models\DocumentRequirementRule`
- Existing `App\Models\DocumentType`
- Existing `App\Models\GradeLevel`
- Existing database schema
- Existing admin layout and shared Blade components

## Important Business Rules

- A rule must belong to a valid document type.
- A rule may optionally target a grade level.
- A rule may optionally target a student type.
- `is_required` is normalized as a boolean because unchecked HTML checkboxes are not submitted.
- `require_if_no_existing_copy` is normalized as a boolean for the same reason.
- No schema or permission changes are introduced during this migration.

## Integration Points

- Document Types module
- Student document verification
- Admission requirements
- Enrollment requirements
- Existing admin sidebar/menu links

## Migration Status

Current phase: module routes, controller, views, requests, service, actions, provider, and tests have been introduced.

Original controller remains preserved in `app/Http/Controllers/Admin` for rollback safety.