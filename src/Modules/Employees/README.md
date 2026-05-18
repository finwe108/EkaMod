# Employees Module

## Purpose

The Employees module manages employee records within the school enterprise system.

This module serves as the central personnel domain for:

- teaching employees
- non-teaching employees
- employee user accounts
- employee role assignments
- teacher profile synchronization

This module is part of the modular monolith migration and preserves existing business behavior from the original Laravel monolith.

---

# Responsibilities

## Employee Management

Handles:

- employee creation
- employee updates
- employee deletion
- employee profile viewing
- employee department assignment
- employment status tracking

---

## Teacher Synchronization

Teaching employees automatically synchronize with the `teachers` table through:

```text
EmployeeTeacherSyncService