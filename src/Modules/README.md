# Modules Architecture Guide

## Purpose

This directory contains modular monolith modules for the school enterprise system.

The system is still one Laravel application. These modules are not microservices.

---

# Core Rules

- Do not break existing URLs.
- Do not rename existing route names.
- Do not change middleware or permissions during migration.
- Do not change database schema unless planned separately.
- Copy before deleting old code.
- Keep models in `app/Models` during early migration.
- Prefer simple services/actions over excessive abstraction.
- Avoid creating tiny modules for every page.

---

# Module Naming

Use domain-based module names.

Good:

```text
Employees
Teachers
Students
Admissions
Finance
DocumentTypes
DocumentRequirementRules