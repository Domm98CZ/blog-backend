# API Documentation

This API follows REST principles and communicates using JSON. Authentication is handled via Bearer tokens, which are obtained upon login or registration.

## Base URL

All API endpoints are prefixed with:

```
/api/
```

## Authentication

The authentication system uses Bearer tokens. Upon successful login or registration, a token is returned, which must be included in subsequent requests using the `Authorization` header:

```
Authorization: Bearer <your_token>
```

### Endpoints:

#### Login
- **POST** `/api/auth/login`
- Returns a Bearer token on success.
- Does not require Bearer token for requests.

##### Example request

```json
{
	"email": "admin@domm.cz",
	"password": "demo1234"
}
```

##### Example response

```json
{
    "app": {
        "datetime": "2025-03-23T16:42:57+01:00",
        "version": 1.0
    },
    "response": {
        "code": 200,
        "status": "success",
        "message": {
            "token": "IR0UAm6w9gYZFxdyFKYW1jGxLHIiEnBi",
            "expires_at": {
                "date": "2025-03-24 16:41:16.694726",
                "timezone_type": 3,
                "timezone": "Europe/Prague"
            }
        }
    }
}
```

#### Register
- **POST** `/api/auth/register`
- Creates a new user account and returns a Bearer token.
- Does not require Bearer token for requests.

##### Example request

```json
{
    "email": "frantisekdemak@domm.cz",
    "password": "demo1234",
    "name": "František Demák"
}
```

##### Example response

```json
{
    "app": {
        "datetime": "2025-03-23T15:55:45+01:00",
        "version": 1.0
    },
    "response": {
        "code": 200,
        "status": "success",
        "message": {
            "token": "LSOCnNNOxQCnBkIQcK9xxhrVsd6lyei2",
            "expires_at": {
                "date": "2025-03-24 15:55:45.668033",
                "timezone_type": 3,
                "timezone": "Europe/Prague"
            }
        }
    }
}
```

## Users (Admin Only)

These endpoints require a valid Bearer token and are restricted to users with the `Admin` role.

#### Get all users
- **GET** `/api/users`
- Returns a list of registered users.

##### Example response

```json
{
    "app": {
        "datetime": "2025-03-22T21:09:32+01:00",
        "version": 1.0
    },
    "response": {
        "2": {
            "id": 2,
            "email": "author@domm.cz",
            "name": "Author",
            "role": "author",
            "createdAt": {
                "date": "2025-03-22 20:15:26.560899",
                "timezone_type": 3,
                "timezone": "Europe/Prague"
            },
            "updatedAt": {
                "date": "2025-03-22 20:15:26.560899",
                "timezone_type": 3,
                "timezone": "Europe/Prague"
            },
            "articles": []
        },
        "3": {
            "id": 3,
            "email": "reader@domm.cz",
            "name": "Čtenář",
            "role": "reader",
            "createdAt": {
                "date": "2025-03-22 20:15:26.560899",
                "timezone_type": 3,
                "timezone": "Europe/Prague"
            },
            "updatedAt": {
                "date": "2025-03-22 20:15:26.560899",
                "timezone_type": 3,
                "timezone": "Europe/Prague"
            },
            "articles": []
        },
        "1": {
            "id": 1,
            "email": "admin@domm.cz",
            "name": "Demo",
            "role": "admin",
            "createdAt": {
                "date": "2025-03-22 20:15:26.560899",
                "timezone_type": 3,
                "timezone": "Europe/Prague"
            },
            "updatedAt": {
                "date": "2025-03-22 20:48:02.430175",
                "timezone_type": 3,
                "timezone": "Europe/Prague"
            },
            "articles": []
        }
    }
}
```

#### Get a specific user
- **GET** `/api/users/{id}`
- Retrieves details of a user by ID.

```json
{
    "app": {
        "datetime": "2025-03-22T21:09:42+01:00",
        "version": 1
    },
    "response": {
        "id": 2,
        "email": "author@domm.cz",
        "name": "Author",
        "role": "author",
        "createdAt": {
            "date": "2025-03-22 20:15:26.560899",
            "timezone_type": 3,
            "timezone": "Europe/Prague"
        },
        "updatedAt": {
            "date": "2025-03-22 20:15:26.560899",
            "timezone_type": 3,
            "timezone": "Europe/Prague"
        },
        "articles": {
            "2": {
                "id": 2,
                "title": "Lorem ipsum dolor sit amet",
                "content": "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Fusce gravida magna ante, nec egestas tortor scelerisque at. Quisque est nisi, ultrices sit amet hendrerit in, feugiat nec lacus. Nam eleifend lacus quis velit volutpat consectetur. Curabitur vitae imperdiet sapien. Curabitur sit amet ex tristique erat rhoncus egestas sit amet vitae nibh. Aenean sagittis feugiat scelerisque. Praesent sodales cursus nisi nec viverra. Nullam rutrum neque vitae libero posuere elementum sed vitae leo. Praesent vitae varius dolor. Quisque dictum nisl eu bibendum ullamcorper. Phasellus blandit, justo id egestas auctor, ex nunc dictum enim, semper auctor nunc tellus eget dui.",
                "author": {
                    "id": 2,
                    "email": "author@domm.cz",
                    "name": "Author",
                    "role": "author",
                    "createdAt": {
                        "date": "2025-03-22 20:15:26.560899",
                        "timezone_type": 3,
                        "timezone": "Europe/Prague"
                    },
                    "updatedAt": {
                        "date": "2025-03-22 20:15:26.560899",
                        "timezone_type": 3,
                        "timezone": "Europe/Prague"
                    },
                    "articles": []
                },
                "createdAt": {
                    "date": "2025-03-22 20:15:26.567500",
                    "timezone_type": 3,
                    "timezone": "Europe/Prague"
                },
                "updatedAt": {
                    "date": "2025-03-22 20:15:26.567500",
                    "timezone_type": 3,
                    "timezone": "Europe/Prague"
                }
            },
            "3": {
                "id": 3,
                "title": "Nam non nibh sed quam malesuada rhoncus at ut est",
                "content": "Lorem ipsum  sit amet, consectetur adipiscing elit. Fusce gravida magna ante, nec egestas tortor scelerisque at. Quisque est nisi, ultrices sit amet hendrerit in, feugiat nec lacus. Nam eleifend lacus quis velit volutpat consectetur. Curabitur vitae imperdiet sapien. Curabitur sit amet ex tristique erat rhoncus egestas sit amet vitae nibh. Aenean sagittis feugiat scelerisque. Praesent sodales cursus nisi nec viverra. Nullam rutrum neque vitae libero posuere elementum sed vitae leo. Praesent vitae varius dolor. Quisque dictum nisl eu bibendum ullamcorper. Phasellus blandit, justo id egestas auctor, ex nunc dictum enim, semper auctor nunc tellus eget dui.",
                "author": {
                    "id": 2,
                    "email": "author@domm.cz",
                    "name": "Author",
                    "role": "author",
                    "createdAt": {
                        "date": "2025-03-22 20:15:26.560899",
                        "timezone_type": 3,
                        "timezone": "Europe/Prague"
                    },
                    "updatedAt": {
                        "date": "2025-03-22 20:15:26.560899",
                        "timezone_type": 3,
                        "timezone": "Europe/Prague"
                    },
                    "articles": []
                },
                "createdAt": {
                    "date": "2025-03-22 20:15:26.567500",
                    "timezone_type": 3,
                    "timezone": "Europe/Prague"
                },
                "updatedAt": {
                    "date": "2025-03-22 20:15:26.567500",
                    "timezone_type": 3,
                    "timezone": "Europe/Prague"
                }
            },
            "4": {
                "id": 4,
                "title": "Phasellus pharetra vestibulum ex, a dignissim urna pretium at",
                "content": "Phasellus pharetra vestibulum ex, a dignissim urna pretium at. Aenean rhoncus, lorem et accumsan cursus, ligula nunc faucibus eros, non mollis nulla sem nec neque. Cras purus velit, tristique in dolor non, elementum aliquet lectus. Sed mollis molestie justo at fringilla. Curabitur feugiat sed mauris at elementum. Nullam tempus tincidunt enim, sit amet tincidunt justo luctus non. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. In pulvinar, orci vel pulvinar mollis, diam erat varius diam, a pretium dolor sem vel velit. Mauris pharetra luctus quam id imperdiet. Nulla ultrices dignissim sem ac commodo. Donec non quam hendrerit, fringilla diam in, porta ipsum. Morbi nec fringilla ligula. Vestibulum vulputate justo nisi, ut fermentum leo tempus et.",
                "author": {
                    "id": 2,
                    "email": "author@domm.cz",
                    "name": "Author",
                    "role": "author",
                    "createdAt": {
                        "date": "2025-03-22 20:15:26.560899",
                        "timezone_type": 3,
                        "timezone": "Europe/Prague"
                    },
                    "updatedAt": {
                        "date": "2025-03-22 20:15:26.560899",
                        "timezone_type": 3,
                        "timezone": "Europe/Prague"
                    },
                    "articles": []
                },
                "createdAt": {
                    "date": "2025-03-22 20:15:26.567500",
                    "timezone_type": 3,
                    "timezone": "Europe/Prague"
                },
                "updatedAt": {
                    "date": "2025-03-22 20:15:26.567500",
                    "timezone_type": 3,
                    "timezone": "Europe/Prague"
                }
            }
        }
    }
}
```

#### Create a user
- **POST** `/api/users`
- Creates a new user account.

##### Example request

```json
{
    "email": "testovaci@email.cz",
    "password": "demo1234",
    "name": "Demo",
    "role": "admin"
}
```

##### Example response

```json
{
    "app": {
        "datetime": "2025-03-22T20:53:57+01:00",
        "version": 1.0
    },
    "response": {
        "id": 7,
        "email": "testovaci@email.cz",
        "name": "Demo",
        "role": "admin",
        "createdAt": {
            "date": "2025-03-22 20:53:57.015885",
            "timezone_type": 3,
            "timezone": "Europe/Prague"
        },
        "updatedAt": {
            "date": "2025-03-22 20:53:57.015889",
            "timezone_type": 3,
            "timezone": "Europe/Prague"
        },
        "articles": []
    }
}
```

#### Update a user
- **PUT** `/api/users/{id}`
- Updates details of a specific user.

##### Example request

```json
{
    "email": "testovaci@email.cz",
    "name": "Demo123",
    "role": "admin"
}
```

##### Example response

```json
{
    "app": {
        "datetime": "2025-03-22T20:49:23+01:00",
        "version": 1
    },
    "response": {
        "id": 5,
        "email": "testovaci@email.cz",
        "name": "Demo123",
        "role": "admin",
        "createdAt": {
            "date": "2025-03-22 20:45:39.053875",
            "timezone_type": 3,
            "timezone": "Europe/Prague"
        },
        "updatedAt": {
            "date": "2025-03-22 20:49:23.217606",
            "timezone_type": 3,
            "timezone": "Europe/Prague"
        },
        "articles": []
    }
}
```

#### Delete a user
- **DELETE** `/api/users/{id}`
- Permanently removes a user.

##### Example response

```json
{
    "app": {
        "datetime": "2025-03-22T20:54:02+01:00",
        "version": 1
    },
    "response": {
        "code": 200,
        "status": "success",
        "message": "UserEntity with id \"7\" was removed."
    }
}
```

## Articles (Role-Based Access Control)

### Permissions:
- `Reader`: Can view articles.
- `Author`: Can create and manage their own articles.
- `Admin`: Full access.

#### Get all articles
- **GET** `/api/articles`
- Returns a list of all articles.

##### Example response

```json
{
    "app": {
        "datetime": "2025-03-22T21:24:07+01:00",
        "version": 1
    },
    "response": {
        "1": {
            "id": 1,
            "title": "Technician, Second Class and Third Class",
            "content": "Holly recalculated the ship’s course, but only after a three-hour conversation about toast. Starbug’s engines hummed as Lister searched for the last remaining curry in deep space. Meanwhile, Kryten polished a non-existent smudge on a panel that no one had ever looked at before. The Cat adjusted his outfit for the 17th time that morning—because a man has to look good even in a parallel universe. “Engage the bazookoid safety protocols,” Rimmer declared, just as the vending machine refused to dispense his favourite pudding. Time travel paradoxes were nothing compared to the mystery of where Lister’s socks disappeared to. In an alternate dimension, Ace Rimmer was busy saving yet another planet, while on this ship, someone just spilled a cup of tea into the main control panel. Business as usual.",
            "author": {
                "id": 1,
                "email": "admin@domm.cz",
                "name": "Demo",
                "role": "admin",
                "createdAt": {
                    "date": "2025-03-22 20:15:26.560899",
                    "timezone_type": 3,
                    "timezone": "Europe/Prague"
                },
                "updatedAt": {
                    "date": "2025-03-22 20:48:02.430175",
                    "timezone_type": 3,
                    "timezone": "Europe/Prague"
                },
                "articles": []
            },
            "createdAt": {
                "date": "2025-03-22 20:15:26.567500",
                "timezone_type": 3,
                "timezone": "Europe/Prague"
            },
            "updatedAt": {
                "date": "2025-03-22 20:15:26.567500",
                "timezone_type": 3,
                "timezone": "Europe/Prague"
            }
        },
        "2": {
            "id": 2,
            "title": "Lorem ipsum dolor sit amet",
            "content": "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Fusce gravida magna ante, nec egestas tortor scelerisque at. Quisque est nisi, ultrices sit amet hendrerit in, feugiat nec lacus. Nam eleifend lacus quis velit volutpat consectetur. Curabitur vitae imperdiet sapien. Curabitur sit amet ex tristique erat rhoncus egestas sit amet vitae nibh. Aenean sagittis feugiat scelerisque. Praesent sodales cursus nisi nec viverra. Nullam rutrum neque vitae libero posuere elementum sed vitae leo. Praesent vitae varius dolor. Quisque dictum nisl eu bibendum ullamcorper. Phasellus blandit, justo id egestas auctor, ex nunc dictum enim, semper auctor nunc tellus eget dui.",
            "author": {
                "id": 2,
                "email": "author@domm.cz",
                "name": "Author",
                "role": "author",
                "createdAt": {
                    "date": "2025-03-22 20:15:26.560899",
                    "timezone_type": 3,
                    "timezone": "Europe/Prague"
                },
                "updatedAt": {
                    "date": "2025-03-22 20:15:26.560899",
                    "timezone_type": 3,
                    "timezone": "Europe/Prague"
                },
                "articles": []
            },
            "createdAt": {
                "date": "2025-03-22 20:15:26.567500",
                "timezone_type": 3,
                "timezone": "Europe/Prague"
            },
            "updatedAt": {
                "date": "2025-03-22 20:15:26.567500",
                "timezone_type": 3,
                "timezone": "Europe/Prague"
            }
        },
        "3": {
            "id": 3,
            "title": "Nam non nibh sed quam malesuada rhoncus at ut est",
            "content": "Lorem ipsum  sit amet, consectetur adipiscing elit. Fusce gravida magna ante, nec egestas tortor scelerisque at. Quisque est nisi, ultrices sit amet hendrerit in, feugiat nec lacus. Nam eleifend lacus quis velit volutpat consectetur. Curabitur vitae imperdiet sapien. Curabitur sit amet ex tristique erat rhoncus egestas sit amet vitae nibh. Aenean sagittis feugiat scelerisque. Praesent sodales cursus nisi nec viverra. Nullam rutrum neque vitae libero posuere elementum sed vitae leo. Praesent vitae varius dolor. Quisque dictum nisl eu bibendum ullamcorper. Phasellus blandit, justo id egestas auctor, ex nunc dictum enim, semper auctor nunc tellus eget dui.",
            "author": {
                "id": 2,
                "email": "author@domm.cz",
                "name": "Author",
                "role": "author",
                "createdAt": {
                    "date": "2025-03-22 20:15:26.560899",
                    "timezone_type": 3,
                    "timezone": "Europe/Prague"
                },
                "updatedAt": {
                    "date": "2025-03-22 20:15:26.560899",
                    "timezone_type": 3,
                    "timezone": "Europe/Prague"
                },
                "articles": []
            },
            "createdAt": {
                "date": "2025-03-22 20:15:26.567500",
                "timezone_type": 3,
                "timezone": "Europe/Prague"
            },
            "updatedAt": {
                "date": "2025-03-22 20:15:26.567500",
                "timezone_type": 3,
                "timezone": "Europe/Prague"
            }
        },
        "4": {
            "id": 4,
            "title": "Phasellus pharetra vestibulum ex, a dignissim urna pretium at",
            "content": "Phasellus pharetra vestibulum ex, a dignissim urna pretium at. Aenean rhoncus, lorem et accumsan cursus, ligula nunc faucibus eros, non mollis nulla sem nec neque. Cras purus velit, tristique in dolor non, elementum aliquet lectus. Sed mollis molestie justo at fringilla. Curabitur feugiat sed mauris at elementum. Nullam tempus tincidunt enim, sit amet tincidunt justo luctus non. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. In pulvinar, orci vel pulvinar mollis, diam erat varius diam, a pretium dolor sem vel velit. Mauris pharetra luctus quam id imperdiet. Nulla ultrices dignissim sem ac commodo. Donec non quam hendrerit, fringilla diam in, porta ipsum. Morbi nec fringilla ligula. Vestibulum vulputate justo nisi, ut fermentum leo tempus et.",
            "author": {
                "id": 2,
                "email": "author@domm.cz",
                "name": "Author",
                "role": "author",
                "createdAt": {
                    "date": "2025-03-22 20:15:26.560899",
                    "timezone_type": 3,
                    "timezone": "Europe/Prague"
                },
                "updatedAt": {
                    "date": "2025-03-22 20:15:26.560899",
                    "timezone_type": 3,
                    "timezone": "Europe/Prague"
                },
                "articles": []
            },
            "createdAt": {
                "date": "2025-03-22 20:15:26.567500",
                "timezone_type": 3,
                "timezone": "Europe/Prague"
            },
            "updatedAt": {
                "date": "2025-03-22 20:15:26.567500",
                "timezone_type": 3,
                "timezone": "Europe/Prague"
            }
        }
    }
}
```

#### Get an article by ID
- **GET** `/api/articles/{id}`
- Retrieves details of a specific article.

##### Example response

```json
{
    "app": {
        "datetime": "2025-03-22T21:24:09+01:00",
        "version": 1
    },
    "response": {
        "id": 1,
        "title": "Technician, Second Class and Third Class",
        "content": "Holly recalculated the ship’s course, but only after a three-hour conversation about toast. Starbug’s engines hummed as Lister searched for the last remaining curry in deep space. Meanwhile, Kryten polished a non-existent smudge on a panel that no one had ever looked at before. The Cat adjusted his outfit for the 17th time that morning—because a man has to look good even in a parallel universe. “Engage the bazookoid safety protocols,” Rimmer declared, just as the vending machine refused to dispense his favourite pudding. Time travel paradoxes were nothing compared to the mystery of where Lister’s socks disappeared to. In an alternate dimension, Ace Rimmer was busy saving yet another planet, while on this ship, someone just spilled a cup of tea into the main control panel. Business as usual.",
        "author": {
            "id": 1,
            "email": "admin@domm.cz",
            "name": "Demo",
            "role": "admin",
            "createdAt": {
                "date": "2025-03-22 20:15:26.560899",
                "timezone_type": 3,
                "timezone": "Europe/Prague"
            },
            "updatedAt": {
                "date": "2025-03-22 20:48:02.430175",
                "timezone_type": 3,
                "timezone": "Europe/Prague"
            },
            "articles": []
        },
        "createdAt": {
            "date": "2025-03-22 20:15:26.567500",
            "timezone_type": 3,
            "timezone": "Europe/Prague"
        },
        "updatedAt": {
            "date": "2025-03-22 20:15:26.567500",
            "timezone_type": 3,
            "timezone": "Europe/Prague"
        }
    }
}
```

#### Create an article
- **POST** `/api/articles`
- Requires `Author` or `Admin` role.

##### Example request

```json
{
    "title": "Testovací článek",
    "content": "Lorem ipsum asi.."
}
```

##### Example response

```json
{
    "app": {
        "datetime": "2025-03-22T21:25:17+01:00",
        "version": 1
    },
    "response": {
        "id": 7,
        "title": "Testovací článek",
        "content": "Lorem ipsum asi..",
        "author": {
            "id": 4,
            "email": "domm98cz@gmail.com",
            "name": "Dominik Procházka",
            "role": "author",
            "createdAt": {
                "date": "2025-03-22 20:15:28.448082",
                "timezone_type": 3,
                "timezone": "Europe/Prague"
            },
            "updatedAt": {
                "date": "2025-03-22 21:24:54.419907",
                "timezone_type": 3,
                "timezone": "Europe/Prague"
            },
            "articles": []
        },
        "createdAt": {
            "date": "2025-03-22 21:25:17.573073",
            "timezone_type": 3,
            "timezone": "Europe/Prague"
        },
        "updatedAt": {
            "date": "2025-03-22 21:25:17.573081",
            "timezone_type": 3,
            "timezone": "Europe/Prague"
        }
    }
}
```

#### Update an article
- **PUT** `/api/articles/{id}`
- Requires `Author` (for own articles) or `Admin`.

##### Example request

```json
{
    "title": "Testovací článek",
    "content": "Lorem ipsum opravdu.."
}
```

##### Example response

```json
{
    "app": {
        "datetime": "2025-03-22T21:25:32+01:00",
        "version": 1
    },
    "response": {
        "id": 7,
        "title": "Testovací článek",
        "content": "Lorem ipsum opravdu..",
        "author": {
            "id": 4,
            "email": "domm98cz@gmail.com",
            "name": "Dominik Procházka",
            "role": "author",
            "createdAt": {
                "date": "2025-03-22 20:15:28.448082",
                "timezone_type": 3,
                "timezone": "Europe/Prague"
            },
            "updatedAt": {
                "date": "2025-03-22 21:24:54.419907",
                "timezone_type": 3,
                "timezone": "Europe/Prague"
            },
            "articles": []
        },
        "createdAt": {
            "date": "2025-03-22 21:25:17.573073",
            "timezone_type": 3,
            "timezone": "Europe/Prague"
        },
        "updatedAt": {
            "date": "2025-03-22 21:25:32.653954",
            "timezone_type": 3,
            "timezone": "Europe/Prague"
        }
    }
}
```

#### Delete an article
- **DELETE** `/api/articles/{id}`
- Requires `Author` (for own articles) or `Admin`.

##### Example response

```json
{
    "app": {
        "datetime": "2025-03-22T21:25:38+01:00",
        "version": 1.0
    },
    "response": {
        "code": 200,
        "status": "success",
        "message": "ArticleEntity with id \"7\" was removed."
    }
}
```

## Error Handling

The API returns standard HTTP status codes:
- `200 OK` – Request successful.
- `400 Bad Request` – Invalid input.
- `401 Unauthorized` – Missing or invalid token.
- `404 Not Found` – Resource does not exist.
- `500 Internal Server Error` – Server-side issue.

For detailed API interactions, refer to the provided **Swagger documentation** and **Insomnia/Postman export files** in `resources/files/`.
Or for live generated Swagger documentation use your project - http://localhost:8080/api/swagger.json.

---

_For more details, check out the documentation files in `.docs/`._
