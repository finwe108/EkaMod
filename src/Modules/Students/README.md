# Students Module (Planned Consolidation)

## Purpose

The Students domain represents the student-facing portal and related student account/profile functionality within the school enterprise system.

This README currently documents student-facing modules that were migrated incrementally during the modular monolith transition and may later be consolidated into a unified `Students` module.

---

# Current Student-Facing Modules

The following modules currently exist as separate transitional modules during the migration process:

| Module | Responsibility |
|---|---|
| StudentDocumentUploads | Student document uploads |
| StudentProfiles | Student profile viewing and editing |
| StudentAccounts | Student username/email/account management |
| StudentPasswords | Student password changes |

The student dashboard currently remains inside the original monolith structure intentionally to avoid premature fragmentation.

---

# Migration Strategy

The current architecture uses temporary smaller modules to allow:

- safe incremental migration
- low-risk refactoring
- backward compatibility
- isolated testing
- gradual service/action extraction

These modules may later be consolidated into:

```text
Modules/Students/