# Document Request Monitoring

## Status

Development

## Purpose

The Document Request Monitoring feature allows authorized school
personnel to monitor student document requests and their lifecycle.

## Scope

The feature will allow administrators to:

- View document requests
- Search requests by student
- Filter requests by document type
- Filter requests by status
- Review submitted requests
- Approve/verify requests
- Reject requests with remarks
- Cancel requests when appropriate
- Track request history
- Handle repeat requests after cancellation

## SLA Monitoring

The SLA (Service Level Agreement) monitoring feature helps authorized personnel track document requests against configured processing deadlines.

### SLA Rules

- SLA monitoring applies only to document types with SLA enabled.
- The SLA start time is determined by the verification time, the configured cutoff time, and the school working calendar.
- Working days follow the school's configured working-day rules, including applicable calendar exceptions.
- Requests may be classified as **Not Started, On Time, Due Soon, Overdue, Completed On Time,** or **Completed Late**.
- Cancelled and unavailable requests are excluded from active SLA monitoring.
- Completed requests are classified according to their completion time relative to the SLA deadline.

### Recalculating Deadlines

The `document-requests:sla-recalculate` Artisan command recalculates due dates for existing active document request SLAs. Use the `--dry-run` option to preview changes without saving them. The `--request=ID` option limits recalculation to items belonging to a specific request.

### Testing

SLA service, lifecycle, and working-calendar tests should pass before the feature is considered ready for release.

## Related Existing Tables

- students
- enrollments
- document_types
- document_requirement_rules
- student_documents
- student_document_uploads
- users