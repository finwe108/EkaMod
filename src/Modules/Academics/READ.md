# Academics Module

## Purpose

The Academics module manages academic configuration areas within the School Enterprise System.

This module is part of the gradual modular monolith migration and currently owns subject management. It may later include grade levels, grading profiles, terms, curriculum setup, and other academic configuration workflows.

---

## Current Responsibilities

### Subjects

The module currently manages administrative subject CRUD operations.

Subject routes preserve existing URLs and route names:

| Method | URL | Route Name | Purpose |
|---|---|---|---|
| GET | /admin/subjects | admin.subjects.index | List subjects |
| GET | /admin/subjects/create | admin.subjects.create | Show create form |
| POST | /admin/subjects | admin.subjects.store | Store subject |
| GET | /admin/subjects/{subject} | admin.subjects.show | Show subject |
| GET | /admin/subjects/{subject}/edit | admin.subjects.edit | Show edit form |
| PUT/PATCH | /admin/subjects/{subject} | admin.subjects.update | Update subject |
| DELETE | /admin/subjects/{subject} | admin.subjects.destroy | Delete subject |

---

## Current Structure

```text
Modules/Academics/
├── Http/Controllers/Admin
├── Requests/Admin
├── Services/Admin
├── Actions/Admin
├── routes
├── resources/views/admin/subjects
├── Providers
├── Tests/Feature
└── README.md
```

---

## Preserved During Migration

The migration preserves:

- existing subject URLs
- existing subject route names
- existing admin middleware
- existing RBAC behavior
- existing `App\Models\Subject`
- existing database schema
- existing subject CRUD behavior

---

## Dependencies

The current Subjects area depends on:

- `App\Models\Subject`
- `App\Models\GradeLevel`
- existing admin authentication
- existing role middleware
- existing layout and shared Blade components

---

## Grade Levels

Grade Levels belong to the Academics domain, but their admin management is not currently migrated.

A legacy `GradeLevelController` exists under:

```text
app/Http/Controllers/Admin/GradeLevelController.php
```

However, it is not currently routed and does not fully match the current `GradeLevel` model.

Current model fillable fields:

```text
name
code
```

Legacy controller references fields not currently fillable:

```text
sort_order
department
```

Before enabling grade level admin management, verify the real database schema and decide whether fields such as `sort_order`, `department`, and `code` should be supported.

---

## Future Academic Areas

Potential future areas for this module:

- Grade Levels
- Grading Profiles
- Academic Terms
- Curriculum setup
- Subject groupings
- Academic year rules
- DepEd/CHED academic configuration

These should be migrated only when active routes/controllers exist or when the feature is intentionally introduced.

---

## Migration Notes

Do not move models into this module yet.

For now, keep Eloquent models under:

```text
app/Models
```

This preserves compatibility with the existing monolith while the modular structure matures.

---

## Current Migration Status

### Completed

- Subject routes moved into `Modules/Academics`
- Subject controller moved into module
- Subject views moved into module namespace
- Subject requests added
- Subject service added
- Subject actions added
- Subject route tests added

### Pending

- Grade Level admin management
- Grading profile management
- Curriculum-related features
- Academic term management
