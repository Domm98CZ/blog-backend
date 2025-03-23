# Configuration Guide

This project is configured using a `.env` file. Before running the installation, ensure that the `.env` file exists and contains the required settings.

## Environment Variables

Create a `.env` file in the project's root directory (or copy the provided `.env.example`):

```sh
cp .env.example .env
```

Then, open `.env` and configure the following parameters:

```ini
DATABASE_HOST=postgresql
DATABASE_NAME=blog_database
DATABASE_USER=backend
DATABASE_PASSWORD=T0PS3CR3T!
```

### Explanation of Parameters

- `DATABASE_HOST` – The hostname of the database server (default: `postgresql`).
- `DATABASE_NAME` – The name of the database used by the application (default: `blog_database`).
- `DATABASE_USER` – The database username (default: `backend`).
- `DATABASE_PASSWORD` – The database password (default: `T0PS3CR3T!`).

## Nette Configuration

The project also includes standard Nette configuration files, but these are not actively used for modifying runtime parameters. Most settings should be adjusted via the `.env` file.

---

_For more details, check out the documentation files in `.docs/`._
