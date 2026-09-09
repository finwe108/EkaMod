# Document Request SLA Policy — Development Roadmap

**Project:** Madana Mohana Colleges, Inc. (MMCI) School Enterprise System (SES)
**Feature:** Document Request SLA Tracking
**Git Branch:** `feature/document-request-sla`
**Status:** Development roadmap / approved policy baseline
**Last Updated:** 2026-09-02

---

## 1. Purpose

This roadmap defines the business rules, technical design, implementation sequence, testing requirements, documentation, and Git workflow for adding Service Level Agreement (SLA) tracking to the existing Document Request Monitoring feature.

The implementation must be incremental and must preserve the existing Document Request lifecycle, routes, authentication, authorization, database relationships, and business rules.

The SLA feature is an **operational tracking layer**. It must not replace or unnecessarily expand the existing document-request lifecycle.

---

# 2. Approved SLA Policy

## 2.1 SLA Granularity

SLA tracking is applied at the **individual document request item** level.

A single document request may contain several document items, and each item may have its own SLA.

Example:

```text
DR-TOR-20260818-0001

Transcript of Records       → 3 working days
Good Moral Certificate      → 2 working days
Certificate of Enrollment   → 1 working day
```

The parent request may therefore contain items with different due dates.

---

## 2.2 Default SLA

The system default is:

> **3 working days**

Individual document types may override the default.

Recommended initial document-type SLAs:

| Document Type | Proposed SLA |
|---|---:|
| Certificate of Enrollment | 1 working day |
| Certificate of Grades | 1 working day |
| Good Moral Certificate | 2 working days |
| Certificate of Completion | 2 working days |
| Transcript of Records | 3 working days |
| Diploma | 3 working days |
| Form 137 / Permanent Record | 5 working days |
| Other standard documents | 3 working days |

These values should be configurable and must not be permanently hard-coded into application logic.

---

# 3. SLA Clock

## 3.1 SLA Start

The fulfillment SLA starts when the document request reaches:

```text
VERIFIED
```

The system should record:

```text
sla_started_at
```

The existing:

```text
requested_at
```

continues to represent when the request was submitted.

The existing:

```text
processing_started_at
```

continues to represent when actual document preparation began.

These timestamps have different meanings and should remain separate.

---

## 3.2 Verification and Fulfillment Time

The system should eventually be capable of measuring:

```text
Verification Time
requested_at → verified_at

Fulfillment Time
sla_started_at → released_at

Overall Turnaround Time
requested_at → released_at
```

This allows MMCI to distinguish verification delays from document-production delays.

---

# 4. Verification Cut-Off Rule

The approved cut-off policy is:

> **Requests verified at or before 3:00 PM count as that working day. Requests verified after 3:00 PM start on the next working day.**

Examples:

```text
10:00 AM verification
→ SLA starts that working day
```

```text
3:00 PM verification
→ SLA starts that working day
```

```text
3:01 PM verification
→ SLA starts on the next working day
```

The cut-off time should be configurable rather than embedded permanently in the SLA calculation logic.

Initial configuration:

```text
Registrar SLA cut-off: 3:00 PM
```

---

# 5. Working-Day Rules

SLA calculations use **working days**, not calendar days.

## 5.1 Weekends

The standard non-working days are:

```text
Saturday
Sunday
```

They do not consume SLA working days.

---

## 5.2 School Holidays

School holidays should not consume SLA working days.

The eventual implementation should support a configurable school working calendar rather than hard-coding individual holiday dates.

---

## 5.3 Special Closures

Special school closures should also be excluded from SLA calculations.

Examples:

- Weather-related closure
- Emergency closure
- Government-declared closure
- Administrative closure

These should eventually be represented in a configurable working-calendar system.

---

# 6. SLA Due Date

Each document request item should have an independently calculated:

```text
sla_due_at
```

The due date is calculated from:

```text
sla_started_at
+
document-specific SLA duration
```

while respecting:

- weekends
- school holidays
- special closures
- configured cut-off time

---

# 7. SLA Completion

For successful fulfillment:

```text
released
```

is the successful terminal state.

When the item is released, the system should record:

```text
sla_completed_at
```

The SLA result is determined by comparing:

```text
sla_completed_at
```

against:

```text
sla_due_at
```

---

# 8. Non-Successful Terminal Outcomes

The existing item lifecycle contains:

```text
released
unavailable
cancelled
```

These remain unchanged.

For:

```text
unavailable
cancelled
```

the SLA clock stops because the item has reached a terminal outcome.

However, these outcomes must **not** be classified as successful on-time completion.

The system should preserve the item's SLA information for historical reporting.

---

# 9. Re-Requests

Cancellation does not allow a request to be reopened or reused.

If a previously cancelled document is requested again:

```text
Old Request
DR-TOR-20260818-0001
→ CANCELLED
```

a new request is created:

```text
New Request
DR-TOR-20260825-0007
→ new SLA
```

The new request receives a completely independent SLA clock.

This preserves historical accountability.

---

# 10. SLA Status

SLA status is a **derived operational state**, not a replacement for the document lifecycle status.

The existing item lifecycle remains:

```text
pending
processing
ready_for_release
released
unavailable
cancelled
```

Recommended derived SLA states:

```text
NOT_STARTED
ON_TIME
DUE_SOON
OVERDUE
COMPLETED_ON_TIME
COMPLETED_LATE
```

Examples:

```text
Document Status: PROCESSING
SLA Status: ON_TIME
```

```text
Document Status: PROCESSING
SLA Status: OVERDUE
```

This prevents unnecessary combinations such as:

```text
pending_overdue
processing_overdue
ready_for_release_overdue
```

---

# 11. Due-Soon Rule

The recommended early-warning threshold is:

> **DUE_SOON means one working day or less remains before the SLA deadline.**

Example:

```text
Due: Thursday 5:00 PM

Wednesday
→ DUE_SOON

Thursday
→ DUE_SOON / Due Today

Friday
→ OVERDUE
```

The exact presentation may be refined during UI implementation.

---

# 12. Grace Period

There is **no automatic grace period**.

If the deadline is:

```text
August 21, 2026 5:00 PM
```

then:

```text
Before 5:00 PM → within SLA
At/after the deadline → due/overdue according to the exact calculation
```

Any future grace period must be an explicit business-policy change and should be configurable.

---

# 13. Urgent Requests

Urgent-request SLA handling is **not part of the initial implementation**.

It may be introduced later with appropriate:

- priority rules
- authorization
- reason
- audit trail
- possible payment/fee integration

Potential future priorities:

```text
NORMAL
URGENT
```

---

# 14. Proposed Database Changes

The initial document-request-item SLA implementation should add:

```text
sla_started_at
sla_due_at
sla_completed_at
```

These fields belong to:

```text
document_request_items
```

The existing lifecycle timestamps remain intact, including:

```text
processing_started_at
ready_at
released_at
```

The implementation must not duplicate or repurpose these fields.

---

# 15. SLA Configuration

The preferred architecture is configurable SLA values.

The system should support:

## System default

```text
3 working days
```

## Document-type override

Example:

```text
TOR
→ 3 working days

Good Moral
→ 2 working days

Certificate of Enrollment
→ 1 working day
```

The exact database location for this configuration must be confirmed against the current `DocumentType` model and migrations before implementation.

No assumptions should be made about the existing schema.

---

# 16. Planned SLA Service

Create a dedicated service:

```text
DocumentRequestSlaService
```

Its responsibilities should include:

- determine whether SLA applies
- determine SLA start time
- calculate SLA due date
- skip non-working days
- honor school closures
- honor the 3:00 PM cut-off
- determine current SLA status
- determine whether an item is overdue
- determine whether an item is due soon
- determine completion result
- calculate SLA durations for reporting

The service should contain SLA business logic so that controllers and Blade views do not contain complicated date calculations.

---

# 17. Lifecycle Integration

The existing:

```text
DocumentRequestItemLifecycleService
```

must remain the authority for item lifecycle transitions.

SLA integration should be incremental.

Important existing transitions:

```text
pending
  ↓
processing
  ↓
ready_for_release
  ↓
released
```

Alternative terminal outcomes:

```text
pending / processing / ready_for_release
  ↓
unavailable

pending / processing / ready_for_release
  ↓
cancelled
```

SLA logic must work with these existing transitions without changing their allowed-transition rules unless a separate business requirement is approved.

---

# 18. Request-Level SLA

The initial SLA is item-level.

The parent request should not receive an independent lifecycle status merely for SLA purposes.

Future request-level reporting may derive values such as:

```text
Request On Time
Request Overdue
Request Completed Late
```

from the statuses of its document items.

This should be designed carefully because a request may contain multiple items with different SLAs.

---

# 19. Work Queue Integration

The existing work queue already supports:

```text
item_status
```

The SLA feature should preserve this.

Future SLA filtering should be additive, for example:

```text
sla=overdue
sla=due_soon
sla=on_time
```

Existing filters must continue working:

```text
search
status
item_status
```

Query-string filters should remain compatible with pagination.

---

# 20. Dashboard Integration

The existing work-summary cards include:

```text
Total Requests
Needs Processing
Processing
Ready for Release
Released
```

SLA work should eventually add:

```text
Overdue
Due Soon
```

The existing cards must continue to work.

SLA summary counts should be based on clearly defined request/item semantics.

Because SLA is calculated at the document-item level, the implementation must explicitly decide whether each dashboard metric represents:

- document items, or
- requests containing matching items.

For consistency with the current work-summary design, request-level dashboard counts should normally count a request only once if it has at least one matching item.

---

# 21. Work Queue Display

Each document item should eventually display useful SLA information.

Example:

```text
Transcript of Records
PROCESSING

Due Aug 21, 2026
2 working days remaining
```

Overdue example:

```text
Transcript of Records
PROCESSING

OVERDUE
Due Aug 18, 2026
2 working days overdue
```

The UI should make SLA state visually scannable without replacing the existing lifecycle status.

---

# 22. Activity Timeline

The existing status-history system already records request lifecycle transitions.

The item lifecycle also records individual item transitions.

The SLA feature should reuse these audit mechanisms rather than creating redundant audit records.

Future timeline entries may display:

```text
Request submitted
Request verified
SLA started
Processing started
Document ready
Document released
```

Where possible, existing timestamps and histories should be used.

---

# 23. Notifications — Future Phase

Notifications should eventually support events such as:

```text
Document due soon
Document overdue
Document released
```

The initial SLA implementation should focus on accurate calculation and visibility before adding automated notifications.

---

# 24. Reporting — Future Phase

Future Registrar reports may include:

```text
Total requests
Total documents
Completed on time
Completed late
Currently overdue
Due soon
Average verification time
Average fulfillment time
Average total turnaround time
SLA compliance percentage
```

Possible breakdowns:

```text
By document type
By month
By registrar/user
By school year
```

Reporting should use stored timestamps and derived SLA calculations rather than duplicating lifecycle data.

---

# 25. Implementation Roadmap

## Phase 1A — Policy and Schema Foundation

Tasks:

- Confirm SLA policy
- Inspect current `DocumentRequestItem` model
- Inspect current item migration
- Inspect current `DocumentType` model
- Inspect current `DocumentType` migrations
- Decide where configurable SLA values belong
- Add SLA timestamps to document request items
- Update model casts/fillable attributes as appropriate
- Add migration tests

Deliverable:

```text
Database can store SLA lifecycle timestamps.
```

---

## Phase 1B — SLA Calculation Service

Create:

```text
DocumentRequestSlaService
```

Implement:

- working-day calculation
- 3:00 PM cut-off
- default SLA
- document-type SLA override
- due-date calculation
- overdue calculation
- due-soon calculation
- completion result

Deliverable:

```text
SLA calculations are centralized and testable.
```

---

## Phase 1C — Lifecycle Integration

Integrate SLA with:

```text
DocumentRequestItemLifecycleService
```

Ensure:

- SLA starts at verification
- processing timestamp remains independent
- release records SLA completion
- unavailable stops the SLA
- cancellation stops the SLA
- existing lifecycle rules remain intact
- status history remains intact

Deliverable:

```text
SLA follows the existing document lifecycle safely.
```

---

## Phase 1D — Work Queue UI

Add:

- SLA status
- due date
- remaining time
- overdue duration
- due-soon indicator

Preserve:

```text
search
status
item_status
pagination
```

Deliverable:

```text
Registrar can immediately identify documents at risk.
```

---

## Phase 1E — SLA Filters and Dashboard

Add:

```text
Overdue
Due Soon
```

Support URL/query filters while preserving existing filters.

Deliverable:

```text
Registrar can focus on overdue and urgent operational work.
```

---

## Phase 1F — Activity Timeline

Expose existing lifecycle history and SLA timestamps in a useful chronological timeline.

Deliverable:

```text
Registrar can see what happened and when.
```

---

## Phase 1G — Notifications

Add configurable notifications for:

- due soon
- overdue
- release

Deliverable:

```text
Registrar receives proactive SLA warnings.
```

---

## Phase 1H — Testing and Documentation

Add:

- unit tests
- feature tests
- edge-case tests
- SLA policy documentation
- implementation documentation
- Git history

Deliverable:

```text
Feature is documented, tested, and ready for production review.
```

---

# 26. Required Test Scenarios

At minimum, test:

## Cut-off

```text
2:59 PM verification
→ same working day

3:00 PM verification
→ same working day

3:01 PM verification
→ next working day
```

## Weekend

```text
Friday
→ Saturday/Sunday excluded
→ Monday continues SLA
```

## Holiday

```text
Working day
→ school holiday
→ next working day
```

## Multiple documents

One request containing different document types must receive different due dates when different SLAs are configured.

## On-time completion

```text
released_at <= sla_due_at
```

→ `COMPLETED_ON_TIME`

## Late completion

```text
released_at > sla_due_at
```

→ `COMPLETED_LATE`

## Overdue

Unreleased item past its due date:

```text
→ OVERDUE
```

## Cancelled

Cancelled item:

```text
→ SLA stops
```

## Unavailable

Unavailable item:

```text
→ SLA stops
```

## Re-request

Cancelled old request + new request:

```text
→ independent SLA
```

## No duplicate lifecycle

Repeated transition attempts must continue to be rejected by the existing lifecycle service.

---

# 27. Backward Compatibility Requirements

The implementation must:

- preserve existing routes
- preserve existing URLs
- preserve authentication
- preserve authorization
- preserve existing document-request lifecycle
- preserve request status history
- preserve item status history
- preserve existing document-request numbering
- preserve existing student/legacy-student behavior
- preserve existing search
- preserve existing request-status filtering
- preserve existing item-status filtering
- preserve pagination
- avoid rewriting unrelated document systems

The existing student-document upload/verification systems must not be rewritten as part of SLA development.

---

# 28. Architecture Rules

The implementation must continue using the existing modular-monolith architecture.

Do not introduce:

- microservices
- CQRS
- event sourcing
- unnecessary repositories
- excessive abstraction
- duplicate lifecycle systems

Use the existing:

```text
Controller
    ↓
Service
    ↓
Model
    ↓
Database
```

pattern where appropriate.

Business rules belong in services rather than Blade templates.

---

# 29. Git Development Workflow

The active branch is:

```bash
feature/document-request-sla
```

Before development:

```bash
cd ~/projects/EkaMod/laravel12

git status

git switch main
git pull --ff-only origin main

git switch feature/document-request-sla
```

Development changes should be committed only when tested.

Before committing:

```bash
git status --short
git diff
```

Stage only relevant files.

Example:

```bash
git add Modules/DocumentRequests
git add app/Enums
git add app/Models
git add database/migrations
git add docs/document-request-sla.md
```

Then:

```bash
git status
git diff --cached --stat
```

Commit:

```bash
git commit -m "feat(document-requests): add SLA tracking"
```

Push:

```bash
git push -u origin feature/document-request-sla
```

---

# 30. Merge Workflow

After testing and review:

```bash
git switch main
git pull --ff-only origin main

git merge --no-ff feature/document-request-sla     -m "Merge feature/document-request-sla"

git push origin main
```

Then optionally return to the feature branch:

```bash
git switch feature/document-request-sla
```

Unrelated existing changes in the repository must not be included in the SLA commit.

---

# 31. Documentation Deliverables

The feature should maintain:

```text
docs/document-request-sla.md
```

The document should contain:

- approved SLA policy
- business rules
- configuration rules
- calculation rules
- cut-off policy
- working-day policy
- lifecycle integration
- examples
- testing rules
- future enhancements

Future technical documentation may include:

```text
docs/features/document-request-sla/
```

if the implementation becomes sufficiently large.

---

# 32. Future Enhancements

After the initial SLA implementation, possible enhancements include:

1. Configurable school calendar
2. Holiday management
3. Special closure management
4. Urgent request priority
5. Automated notifications
6. Registrar workload dashboard
7. SLA performance reports
8. Student-facing request tracking
9. Parent request tracking
10. SLA compliance analytics
11. Document-type performance analysis
12. Historical SLA trend reports

These should be implemented incrementally.

---

# 33. Definition of Done

The initial SLA feature is considered complete when:

- [ ] SLA policy is documented
- [ ] Default SLA is configurable
- [ ] Document-type SLA overrides are configurable
- [ ] SLA starts at verification
- [ ] 3:00 PM cut-off is enforced
- [ ] Working days are correctly calculated
- [ ] Weekends are excluded
- [ ] Configured holidays/closures are excluded
- [ ] SLA due date is stored
- [ ] SLA completion timestamp is stored
- [ ] Overdue detection works
- [ ] Due-soon detection works
- [ ] On-time completion works
- [ ] Late completion works
- [ ] Cancelled items stop SLA tracking
- [ ] Unavailable items stop SLA tracking
- [ ] Re-requests receive new SLAs
- [ ] Existing lifecycle remains intact
- [ ] Existing filters remain intact
- [ ] Work queue displays SLA information
- [ ] Overdue/Due Soon filtering works
- [ ] Tests pass
- [ ] Documentation is complete
- [ ] Only relevant files are committed
- [ ] Feature branch is pushed
- [ ] Feature is reviewed before merging to `main`

---

# 34. Current Status

### Approved

- [x] Document-level SLA
- [x] Default 3-working-day SLA
- [x] SLA begins at verification
- [x] 3:00 PM verification cut-off
- [x] Weekends excluded
- [x] Holidays/closures excluded
- [x] No grace period
- [x] Cancelled requests are not reopened
- [x] Re-requests receive new SLA
- [x] SLA is separate from lifecycle status

### Pending Development

- [ ] Inspect current `DocumentRequestItem` model
- [ ] Inspect current `DocumentRequestItem` migration
- [ ] Inspect current `DocumentType` model
- [ ] Inspect current `DocumentType` migrations
- [ ] Finalize configuration schema
- [ ] Create SLA migration
- [ ] Create SLA service
- [ ] Integrate lifecycle
- [ ] Add UI
- [ ] Add filters
- [ ] Add dashboard metrics
- [ ] Add tests
- [ ] Complete documentation
- [ ] Push branch
- [ ] Review
- [ ] Merge to `main`

---

## 35. Guiding Principle

> **The SLA feature should make the Registrar's work visible and measurable without changing the established Document Request lifecycle.**

The existing system remains the source of truth for document-request and document-item lifecycle states.

The SLA layer answers a separate operational question:

> **"Is this document being completed within the service standard agreed upon by MMCI?"**
