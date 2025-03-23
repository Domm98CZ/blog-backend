# Testing Guide

This project uses **PHPUnit** for automated testing. Tests are organized into three categories:

1. **Unit Tests** – Validate the ACL (Access Control Layer).
2. **Integration Tests** – Ensure business/domain logic functions correctly.
3. **Functional Tests** – Test the API endpoints as described in [API Documentation](api.md).

## Running Tests

To execute all test suites, run the following command:

```sh
composer tests
```

This will automatically discover and execute all PHPUnit test cases.

## Test Categories

### 1. Unit Tests (ACL Layer)
- Located in `tests/Unit/`
- Ensures that access control rules function as expected.
- Example: Checking if users with insufficient permissions are correctly denied access.

### 2. Integration Tests (Business/Domain Logic)
- Located in `tests/Integration/`
- Tests interactions between different components of the system.
- Example: Validating that article creation follows business constraints.

### 3. Functional Tests (API Endpoints)
- Located in `tests/Functional/`
- Sends actual HTTP requests to test the API behavior.
- Example: Ensuring login returns a valid Bearer token.

## Test Environment

- Ensure you are running tests in an isolated environment to avoid modifying real data.

---

_For more details, check out the documentation files in `.docs/`._
