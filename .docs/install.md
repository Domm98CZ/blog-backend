# Installation Guide

Follow these steps to set up the project locally using Docker.

## Prerequisites

Before you begin, ensure you have the following installed:

- [Docker](https://www.docker.com/get-started) (including Docker Compose)
- A free port (default is `8080`, but you can change it in the `docker-compose.yaml` file)
- Already created .env file from [Configuration Guide](.docs/config.md).

## Step 1: Clone the Repository

First, clone the project repository:

```sh
git clone https://github.com/Domm98CZ/blog-backend.git
cd blog-backend
```

## Step 2: Run the Installation Script

To install the necessary dependencies and start the application, run the appropriate script for your operating system:

### For Windows:

```sh
resources/scripts/install.bat
```

### For Linux/macOS:

```sh
resources/scripts/install.sh
```

The script will automatically build and start the Docker containers, download all composer packages and start database migrations.

## Step 3: Access the Application

Once the installation script has finished, open your browser and go to:

```
http://localhost:8080
```

You should see a page with the message: **"It works!"**

If the application doesn't open, check the Docker logs for any errors, or verify the port configuration in the `docker-compose.yaml` file.

## Troubleshooting

- **Docker Not Running**: Ensure Docker is running and accessible.
- **Port Conflicts**: If port 8080 is already in use, you can change the port in the `docker-compose.yaml` file by modifying the `ports` section:
  ```yaml
  ports:
    - "8081:80"
  ```
  Then run the installation script again.

## Next Steps

Once the application is up and running, you can proceed to next steps:
- [Configuration](.docs/config.md) – Information about environment settings and configuration options.
- [API Reference](.docs/api.md) – Documentation for the project's API endpoints.
- [Testing](.docs/test.md) – Running tests and ensuring code quality.

---

_For more details, check out the documentation files in `.docs/`._
