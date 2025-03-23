# Demo Data

The application provides demo data when running in **Nette Debug Mode**. This ensures that developers can easily test the system with predefined users and sample articles.

## How Demo Data Works

- If **Nette Debug Mode** is enabled, demo data is automatically loaded via migrations.
- Demo data includes sample articles for testing purposes.
- Without Debug Mode, only the default database structure and test user accounts are created.

## Default Test Accounts

The following user accounts are available for testing:

| Email              | Role   | Password  |
|-------------------|--------|----------|
| admin@domm.cz    | Admin  | demo1234 |
| author@domm.cz   | Author | demo1234 |
| reader@domm.cz   | Reader | demo1234 |

### Role Permissions

- **Admin** – Full access, including user management.
- **Author** – Can create and manage their own articles.
- **Reader** – Can only view articles.

## Using Demo Data

To enable demo data, ensure that Nette Debug Mode is turned on. If running locally, this is usually enabled by default.

---

For more information about authentication and API testing, refer to the [API Documentation](api.md).
