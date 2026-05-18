# Modular Monolith Migration Checkpoint

## Project

School Enterprise System modular monolith migration.

## Checkpoint Status

The active route/controller layer has been modularized using an incremental strangler-pattern approach.

Existing URLs, route names, middleware, authentication behavior, RBAC behavior, database schema, and Eloquent models were preserved during migration.

---

## Completed High-Level Migration Work

### Route Splitting

The original large `routes/web.php` was split into smaller route loader files:

```text
routes/public.php
routes/auth.php
routes/admin.php
routes/student.php
routes/teacher.php
```

Later, many route groups were moved into module route files and loaded through module service providers.

---

### PSR-4 Module Autoloading

Composer now autoloads:

```json
"Modules\\": "Modules/"
```

This allows module classes to use namespaces such as:

```php
Modules\Students\Http\Controllers\...
Modules\Teachers\Services\...
Modules\Reports\Http\Controllers\...
```

---

### Module Service Providers

Modules now register their own routes and views through service providers.

This reduced route ownership from the old central route files and moved responsibility into each module.

---

## Completed Modules

### Core / Shared

| Module | Purpose |
|---|---|
| Auth | Login and logout routes |
| Dashboard | Role-aware dashboard and navigation support |
| Notifications | User notification list and read actions |
| PublicSite | Public website pages |
| Reports | Report features, currently SF1 |

### Admin / School Operations

| Module | Purpose |
|---|---|
| Announcements | Announcement CRUD |
| SchoolSettings | School-wide settings |
| SchoolYears | School year management |
| Employees | Employee records, user accounts, teacher sync |
| Sections | Section CRUD and section enrollment helpers |
| Enrollments | Enrollment CRUD, status updates, section AJAX |
| Academics | Academic configuration, currently subjects |

### Documents

| Module | Purpose |
|---|---|
| DocumentTypes | Document type CRUD |
| DocumentRequirementRules | Document requirement rules |
| StudentDocuments | Admin verification/rejection of student documents |

### Students

| Module | Purpose |
|---|---|
| Students | Student-facing portal and admin student management |

The Students module currently includes:

- student dashboard
- student profile
- student account settings
- student password changes
- student document uploads
- admin student CRUD
- admin student credential management

### Teachers

| Module | Purpose |
|---|---|
| Teachers | Teacher portal, teacher profiles, teacher loads, schedules, grades routes |

The Teachers module currently includes:

- teacher-facing classes/grades/attendance/schedule
- admin teacher profile management
- admin teacher load management
- teacher schedule viewing
- teacher load conflict support

### Admissions

| Module | Purpose |
|---|---|
| Admissions | Public admission application and admin admission review |

The Admissions module currently includes:

- public admission application form
- admission application submission
- admission success page
- admin application list
- admin application review
- rejection
- acceptance
- student/user/enrollment creation during acceptance
- admission notifications and email behavior

---

## Reports Module Status

The Reports module currently owns active SF1 routes:

```text
admin.reports.sf1.filter
admin.reports.sf1.print
admin.reports.sf1.queue
admin.reports.sf1.generated
admin.reports.sf1.generated.generate
admin.reports.sf1.generated.download
```

The old SF1 prepare/export/download workflow is marked legacy/unrouted.

---

## Active Route Ownership

At this checkpoint, active route ownership is modularized.

Representative examples:

```text
/admin/students        -> Modules\Students
/admin/teachers        -> Modules\Teachers
/admin/enrollments     -> Modules\Enrollments
/admin/subjects        -> Modules\Academics
/admin/sections        -> Modules\Sections
/admin/employees       -> Modules\Employees
/admin/reports/sf1     -> Modules\Reports
/admission/apply       -> Modules\Admissions
/login                 -> Modules\Auth
/dashboard             -> Modules\Dashboard
/notifications         -> Modules\Notifications
```

---

## Legacy Controllers

Old controllers were moved into `Legacy` namespaces/folders after active route ownership was migrated.

Examples:

```text
app/Http/Controllers/Admin/Legacy
app/Http/Controllers/Auth/Legacy
app/Http/Controllers/Public/Legacy
app/Http/Controllers/Student/Legacy
app/Http/Controllers/Teacher/Legacy
app/Http/Controllers/Legacy
```

These are retained temporarily for rollback/reference safety.

---

## Remaining Non-Legacy Controllers

After cleanup, only these remain outside Legacy:

```text
app/Http/Controllers/Controller.php
app/Http/Controllers/Admin/GradeLevelController.php
```

### Controller.php

This remains intentionally because module controllers extend:

```php
App\Http\Controllers\Controller
```

### GradeLevelController.php

This remains intentionally because it is not currently routed and has schema mismatch concerns.

Current `GradeLevel` model fillable fields:

```text
name
code
```

The legacy controller references fields such as:

```text
sort_order
department
```

Before enabling or migrating grade-level admin management, the real schema and desired business rules must be verified.

---

## Known Follow-ups

### Grade Levels

Decide whether Grade Levels should support:

```text
code
name
sort_order
department
```

Then migrate properly under:

```text
Modules/Academics
```

### Student ID Generation

Admissions acceptance currently preserves legacy student ID generation logic inside the Admissions service.

Future improvement:

```text
Use the shared StudentIdService consistently across admissions and admin student creation.
```

This must be handled carefully because student ID generation is business-critical.

### Dashboard / Navigation

The sidebar is now configuration/service-driven.

Dashboard role filtering has been started so users should only receive role-appropriate stats.

Future improvements:

- module-driven dashboard widgets
- per-role dashboards
- stronger dashboard tests
- module-owned navigation registration

### Reports

The Reports module currently supports SF1.

Future planned reports:

```text
SF2
SF3
SF4
SF5
SF6
SF7
SF8
SF9
SF10
```

These should be added under `Modules/Reports`, not as separate top-level modules.

### Finance

`Modules/Finance` exists as a placeholder only.

Finance routes should not be registered until the module is intentionally implemented.

### Parents

`Modules/Parents` exists as a placeholder only.

No active parent portal migration has been completed yet.

---

## Verification Commands

Use these commands after each migration step:

```bash
composer dump-autoload
php artisan optimize:clear
php artisan route:list
php artisan test
```

To confirm active routes are no longer using old controllers:

```bash
php artisan route:list | grep -E "Admin\\|App\\|Public\\|Auth\\|DashboardController|NotificationController"
```

To list remaining non-legacy controllers:

```bash
find app/Http/Controllers -type f | grep -v Legacy | sort
```

To inspect module structure:

```bash
find Modules -maxdepth 3 -type d | sort
```

---

## Migration Principles Used

- Preserve existing functionality first.
- Refactor incrementally.
- Copy files before deleting or archiving originals.
- Keep route names unchanged.
- Keep URLs unchanged unless the old route was already broken and was intentionally fixed.
- Keep middleware and RBAC behavior unchanged.
- Keep models in `app/Models` during this migration stage.
- Avoid microservices.
- Avoid CQRS/event sourcing.
- Avoid excessive abstraction.
- Prefer Services and Actions only where they improve clarity.
- Keep legacy code temporarily for rollback safety.

---

## Current Status Summary

```text
Active route layer: modularized
Old controllers: archived to Legacy
Tests: passing at latest checkpoint
Database schema: unchanged
Models: still under app/Models
Architecture: modular monolith
```

---

## Recommended Next Phase

Before adding more features:

1. Keep running full tests after each change.
2. Add deeper feature tests for high-risk modules:
   - Admissions acceptance
   - Enrollment creation/status updates
   - Student credential creation
   - Teacher load conflict validation
   - SF1 generation
3. Review placeholder modules:
   - Finance
   - Parents
4. Decide Grade Level schema and migrate under Academics later.
5. Review old Legacy folders only after several stable deployments.
