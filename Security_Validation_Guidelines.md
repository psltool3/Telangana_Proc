# Security Audit and Data Validation Guidelines

## Overview
This document outlines the standard security audit and data validation protocols for the **PC (Purchase Center)**, **Mill**, and **Warehouse** modules. These guidelines are designed to be generic and applicable across all state-specific procurement portals to ensure data integrity and prevent security vulnerabilities such as SQL Injection, Cross-Site Scripting (XSS), and malicious payload injections (e.g., `<script>`, `<img>` tags, or external links).

---

## 1. Field-Level Validation Rules

To maintain strict data hygiene, the following character restrictions must be enforced on all Name and ID fields across the system.

### 1.1 Name Fields (PC Name, Mill Name, Warehouse Name)
Names can contain multiple words but must not contain special symbols that could be used for injection attacks.

*   **Allowed Characters:**
    *   Letters (`a-z`, `A-Z`)
    *   Numbers (`0-9`)
    *   Underscores (`_`)
    *   Hyphens (`-`)
    *   Spaces (` `)
*   **Disallowed Characters:** All other special characters (e.g., `@`, `#`, `$`, `%`, `&`, `*`, `<`, `>`, `/`, `\`, etc.) are strictly prohibited.
*   **Regex Pattern:** `/^[a-zA-Z0-9_\-\s]+$/`

### 1.2 ID Fields (PC ID, Mill ID, Warehouse ID)
Identifiers must be continuous alphanumeric strings. Spaces are strictly forbidden to prevent parsing errors and ensure clean database indexing.

*   **Allowed Characters:**
    *   Letters (`a-z`, `A-Z`)
    *   Numbers (`0-9`)
    *   Underscores (`_`)
    *   Hyphens (`-`)
*   **Disallowed Characters:** Spaces (` `) and all other special characters are strictly prohibited.
*   **Regex Pattern:** `/^[a-zA-Z0-9_\-]+$/`

---

## 2. Implementation Scope

Validation must be implemented at multiple layers of the application to ensure comprehensive security (Defense in Depth).

### 2.1 Client-Side Validation (Frontend Forms)
*   **Disabled by Design:** Client-side validation (such as HTML5 `pattern` attributes and JavaScript `alert()` interruptions) is intentionally disabled for Name and ID fields.
*   **Workflow Requirement:** This ensures that the user is allowed to proceed to the administrative credentials verification popup without being prematurely blocked by browser tooltips or JS alerts.
*   **Error Display:** Once credentials are confirmed and the form is submitted, the backend API will display the exact detailed validation error (e.g., "Error : Name should only contain characters...") if validation fails.

### 2.2 Server-Side Validation (Standard API Endpoints)
*   **API Data Sanitization:** All incoming requests (POST, PUT, GET, etc.) to API endpoints (e.g., `PCAdd.php`, `MillReplicaEdit.php`) must pass the data through strict regex validation checks in PHP.
*   **Rejection Protocol:** If validation fails, the server must immediately reject the request with an appropriate HTTP status code (e.g., 400 Bad Request) and an error message. It must not attempt to sanitize and save partial data.
*   *Purpose:* Core defense against bypassed frontend validation and direct API attacks.

### 2.3 Bulk Data Processing (CSV Uploads)
*   **Row-Level Validation:** For all bulk upload scripts (e.g., `BulkMillReplicaData.php`, `BulkMillDataEdit.php`), every single row in the uploaded CSV must be independently validated.
*   **Error Handling:** If any row contains invalid characters in the Name or ID fields, the system should either:
    1. Reject the specific row and log the error.
    2. Reject the entire file (depending on project requirements), providing a downloadable error report detailing the exact row and field that failed validation.
*   *Purpose:* Prevent injection of malicious data through large dataset imports.

---

## 3. Security Objectives
By strictly adhering to the alphanumeric (plus underscore and hyphen) restrictions above, the system directly mitigates several critical threats:
*   **SQL Injection:** Prevents attackers from using quotes (`'`), semicolons (`;`), or comment syntax (`--`) to manipulate database queries.
*   **Cross-Site Scripting (XSS):** Prevents the insertion of HTML tags (`<script>`, `<img>`, `<iframe>`) or JavaScript event handlers.
*   **Malicious Links:** Prevents users from turning names into clickable links that could redirect other users to phishing sites.
