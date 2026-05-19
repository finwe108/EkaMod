# Admissions Module

## Purpose

The Admissions module owns the public admission application workflow and the administrative admission review workflow.

This module is part of the gradual modular monolith migration of the School Enterprise System.

---

## Responsibilities

## Public Admission

The public admission flow handles:

- admission application form display
- application submission
- active school year assignment
- application number generation
- submitted status assignment
- applicant success page
- admin/registrar notification when an application is submitted

## Admin Admission Review

The admin review flow handles:

- listing admission applications
- filtering by application status
- viewing application details
- marking submitted applications as under review
- rejecting applications with a reason
- accepting applications
- creating student records from accepted applications
- creating student login accounts
- assigning the student role
- creating pending enrollment records
- sending notifications
- emailing temporary passwords

---

## Routes

## Public Routes

| Method | URL | Route Name | Purpose |
|---|---|---|---|
| GET | /admission/apply | public.admission.create | Show public admission form |
| POST | /admission/apply | public.admission.store | Submit admission application |
| GET | /admission/success/{application} | public.admission.success | Show application success page |

## Admin Routes

| Method | URL | Route Name | Purpose |
|---|---|---|---|
| GET | /admin/admission-applications | admin.admission_applications.index | List applications |
| GET | /admin/admission-applications/{admissionApplication} | admin.admission_applications.show | View application |
| POST | /admin/admission-applications/{admissionApplication}/review | admin.admission_applications.review | Mark under review |
| POST | /admin/admission-applications/{admissionApplication}/accept | admin.admission_applications.accept | Accept application |
| POST | /admin/admission-applications/{admissionApplication}/reject | admin.admission_applications.reject | Reject application |

---

## Current Structure

```text
Modules/Admissions/
├── Actions
├── Http/Controllers/Admin
├── Http/Controllers/Public
├── Providers
├── Requests/Admin
├── Requests/Public
├── Services
├── Tests/Feature
├── resources/views/admin
├── resources/views/public
├── routes/admin.php
├── routes/public.php
└── README.md
```

---

## Preserved During Migration

The migration preserves:

- existing public admission URLs
- existing admin admission URLs
- existing route names
- existing admin middleware and RBAC behavior
- existing database schema
- existing Eloquent models in `app/Models`
- existing notifications
- existing email behavior
- existing student account creation behavior
- existing enrollment creation behavior

---

## Key Services

## PublicAdmissionApplicationService

Handles public admission submission:

- assigns active school year when missing
- generates application number
- creates application record
- marks application as submitted
- notifies admins and registrar

## AdminAdmissionReviewService

Handles admin review actions:

- mark under review
- reject
- accept
- create student
- create user
- assign student role
- create enrollment
- notify users
- send temporary password email

---

## Key Actions

## GenerateAdmissionApplicationNumberAction

Generates unique admission application numbers.

Format:

```text
ADM-YYYYMMDD-XXXXXX
```

Example:

```text
ADM-20260516-A1B2C3
```

---

## Important Business Rules

## Application Submission

New applications are stored with:

```text
application_status = submitted
submitted_at = now()
```

If no school year is provided, the active school year is used.

## Under Review

Only applications with:

```text
application_status = submitted
```

are transitioned to:

```text
application_status = under_review
```

## Rejection

Rejected applications require:

```text
rejection_reason
```

and are updated with:

```text
application_status = rejected
reviewed_at = now()
reviewed_by = current admin user
```

## Acceptance

Accepted applications create:

- a student record
- a linked user account
- a student role assignment
- a pending enrollment record

Accepted applications are updated with:

```text
application_status = accepted
accepted_student_id
created_user_id
reviewed_at
reviewed_by
```

---

## Known Follow-up

The current admission acceptance process still uses legacy student ID generation logic inside `AdminAdmissionReviewService`.

Future improvement:

```text
Use App\Services\StudentIdService consistently across admissions and admin student creation.
```

This should be done as a dedicated phase because student ID generation is business-critical.

---

## Dependencies

Models:

- AdmissionApplication
- Student
- User
- Enrollment
- GradeLevel
- SchoolYear
- Section

Notifications:

- AdmissionApplicationSubmittedNotification
- AdmissionApplicationAcceptedNotification
- StudentAccountCreatedNotification

Mail:

- StudentAccountCreatedMail

Services:

- PublicAdmissionApplicationService
- AdminAdmissionReviewService

---

## Migration Status

## Completed

- Public admission routes moved into module
- Admin admission routes moved into module
- Public controller moved into module
- Admin review controller moved into module
- Views moved into module namespace
- Public submission service extracted
- Admin review service extracted
- Admission application number generation action extracted
- Public request moved into module
- Admin rejection request added
- Feature route tests added

## Pending

- Replace legacy admission student ID generation with shared StudentIdService
- Add deeper feature tests for acceptance/rejection workflows
- Add tests for public application submission
- Review email failure behavior during acceptance
- Review transaction boundaries around email/notification side effects
