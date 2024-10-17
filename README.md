# Имеджборд

Легкий оммаж на сайт reactor.cc.
Сделан просто так.

# Стек

* Laravel 11;
* Laravel Sail;
* Laravel Pint;
* Pest;
* Larastan;
* PostgreSQL;
* PgBouncer;
* Spatie Laravel Query Builder;

# Схема БД

![image](https://github.com/user-attachments/assets/cd903f65-e005-4e61-a80c-ed3e14ae0179)

# API

Каждый запрос должен принимать `Header`, для получения данных в формате json:

```
Accept — application/json
```

Запрос может принимать `Accept-Language` для смены языка.

Доступные значения:

1. en (По-умолчанию)
2. ru

## Auth

### Registration

```
POST api/v1/auth/registration
```

Регистрирует пользователя.

Принимает:

* name — имя, мин. 6, макс, 48, уникальное, обязательно
* email — почта, уникальное, обязательно
* password — пароль, базовые правила для пароля, обязательно
* password_confirmation — подтверждение пароля, обязательно

json:

```json
{
    "token": "---token"
}
```

### Login

```
POST api/v1/auth/login
```

Авторизация.

Принимает:

* email — почта, обязательно
* password — пароль, обязательно

json:

```json
{
    "token": "---token"
}
```

### Logout

```
POST api/v1/auth/logout
```

Выход пользователя.

Возвращает статус 204.

### Upload image

```
POST api/v1/auth/image
```

Обновляет изображение текущего пользователя.

Требует токен.

Принимает:

* image — файл, изображение (jpeg,png,jpg), максимум - 2048.

Возвращает:

```json
{
    "data": {
        "id": 23,
        "original_image_url": "http://localhost/storage/images/users/image-name.jpg",
        "preview_image_url": "http://localhost/storage/images/users/image-name-scaled.jpg"
    }
}
```

### Delete image

```
DELETE api/v1/auth/image
```

Удаляет изображение текущего пользователя.

Требует токен.

Возвращает статус 204.

## Users

### Get users

```
GET api/v1/users
```

Возвращает список пользователей.

Принимает:

* filter[username] — необязательно, текст. Поиск по нику.

json:

```json
{
    "data": [
        {
            "id": 1,
            "name": "shock shock",
            "username": "vipshock",
            "image": {
                "id": 23,
                "original_image_url": "http://localhost/storage/images/users/image.jpg",
                "preview_image_url": "http://localhost/storage/images/users/image-scaled.jpg"
            }
        },
        {
            "id": 2,
            "name": "Gary Browning",
            "username": "nalypanimo",
            "image": null
        }
    ]
}
```

### Get user via id

```
GET api/v1/users/{id}
```

Где `id` — id пользователя

Возвращает пользователя по id

json:

```json
{
    "data": {
        "id": 1,
        "name": "shock shock",
        "username": "vipshock",
        "image": {
            "id": 23,
            "original_image_url": "http://localhost/storage/images/users/image.jpg",
            "preview_image_url": "http://localhost/storage/images/users/image-scaled.jpg"
        }
    }
}
```

## Tags

```
GET api/v1/tags
```

Возвращает список тегов в порядке популярности

Принимает:

* filter[name] — необязательно, текст. Поиск по тегам.

json:

```json
{
    "data": [
        {
            "id": 3,
            "name": "крутой сигма"
        },
        {
            "id": 2,
            "name": "я сигма"
        },
        {
            "id": 1,
            "name": "шок"
        }
    ]
}
```

## Posts

### Get posts

```
GET api/v1/posts
```

Возвращает список постов.

Принимает:

* filter[user] — id пользователя, необязательно.

json:

```json
{
    "data": [
        {
            "id": 1,
            "user": {
                "id": 1,
                "username": "vipshock",
                "image": {
                    "id": 23,
                    "original_image_url": "http://localhost/storage/images/users/image.jpg",
                    "preview_image_url": "http://localhost/storage/images/users/image-scaled.jpg"
                }
            },
            "text": "шокич крутой сигма",
            "likes_count": 1,
            "is_liked": true,
            "images": [
                {
                    "id": 2,
                    "original_image_url": "http://localhost/storage/images/posts/image.jpg",
                    "preview_image_url": "http://localhost/storage/images/posts/image-scaled.jpg"
                }
            ],
            "tags": [
                {
                    "id": 1,
                    "name": "шок"
                },
                {
                    "id": 3,
                    "name": "крутой сигма"
                }
            ],
            "created_at": "2024-10-15T23:58:22.000000Z",
            "updated_at": "2024-10-15T23:58:22.000000Z"
        }
    ]
}
```

### Get post via id

```
GET api/v1/posts/{id}
```

Где `id` — id поста

Возвращает пост по id.

json:

```json
{
    "data": {
        "id": 1,
        "user": {
            "id": 1,
            "username": "vipshock",
            "image": {
                "id": 23,
                "original_image_url": "http://localhost/storage/images/users/image.jpg",
                "preview_image_url": "http://localhost/storage/images/users/image-scaled.jpg"
            }
        },
        "text": "шокич крутой сигма",
        "likes_count": 1,
        "is_liked": true,
        "images": [
            {
                "id": 2,
                "original_image_url": "http://localhost/storage/images/posts/image.jpg",
                "preview_image_url": "http://localhost/storage/images/posts/image-scaled.jpg"
            }
        ],
        "tags": [
            {
                "id": 1,
                "name": "шок"
            },
            {
                "id": 3,
                "name": "крутой сигма"
            }
        ],
        "created_at": "2024-10-15T23:58:22.000000Z",
        "updated_at": "2024-10-15T23:58:22.000000Z"
    }
}
```

### Like post

```
POST api/v1/posts/{id}/like
```

Где `id` — id поста.

Поставить лайк на пост.

Требует токен.

Возвращает статус 204.

### Unlike post

```
POST api/v1/posts/{id}/unlike
```

Где `id` — id поста.

Убрать лайк с поста.

Требует токен.

Возвращает статус 204.

### Store post

```
POST api/v1/posts/
```

Создает пост.

Требует токен.

Принимает:

* text — текст, обязательно при отсутствии изображений;
* images — массив изображений (jpeg,png,jpg,gif), максимальный размер - 102400, максимальный размер массива - 10;
* tags — массив тегов, текст, обязательно, максимальный размер массива - 8, максимальный размер тега - 24.

json:

```json
{
    "data": {
        "id": 3,
        "user": {
            "id": 1,
            "username": "vipshock",
            "image": {
                "id": 23,
                "original_image_url": "http://localhost/storage/images/users/image.jpg",
                "preview_image_url": "http://localhost/storage/images/users/image-scaled.jpg"
            }
        },
        "text": "сигмыч сигма сигмовый",
        "images": [
            {
                "id": 29,
                "original_image_url": "http://localhost/storage/images/posts/image.jpg",
                "preview_image_url": "http://localhost/storage/images/posts/image-scaled.jpg"
            }
        ],
        "tags": [
            {
                "id": 19,
                "name": "тег"
            }
        ],
        "created_at": "2024-10-17T17:53:17.000000Z",
        "updated_at": "2024-10-17T17:53:17.000000Z"
    }
}
```

### Update post

```
PUT/PATCH api/v1/posts/{id}
```

Где `id` — id поста

Обновляет пост.

Требует токен.

Принимает:

* text — текст;
* images — массив изображений (jpeg,png,jpg,gif), максимальный размер - 102400, максимальный размер массива - 10;
* tags — массив тегов, текст, обязательно, максимальный размер массива - 8, максимальный размер тега - 24.

json:

```json
{
    "data": {
        "id": 3,
        "user": {
            "id": 1,
            "username": "vipshock",
            "image": {
                "id": 23,
                "original_image_url": "http://localhost/storage/images/users/image.jpg",
                "preview_image_url": "http://localhost/storage/images/users/image-scaled.jpg"
            }
        },
        "text": "сигмыч сигма сигмовый",
        "images": [
            {
                "id": 29,
                "original_image_url": "http://localhost/storage/images/posts/image.jpg",
                "preview_image_url": "http://localhost/storage/images/posts/image-scaled.jpg"
            }
        ],
        "tags": [
            {
                "id": 19,
                "name": "тег"
            }
        ],
        "created_at": "2024-10-17T17:53:17.000000Z",
        "updated_at": "2024-10-17T17:53:17.000000Z"
    }
}
```

### Delete post

```
DELETE api/v1/posts/{id}
```

Где `id` — id поста

Удаляет пост.

Требует токен.

Возвращает статус 204.

## Comments

### Get comments

```
GET api/v1/{model}/{id}/comments
```

Где `model` — название модели (users, posts) и `id` — id пользователя

Возвращает список комментариев с профиля пользователя

json:

```json
{
    "data": [
        {
            "id": 5,
            "user": {
                "id": 1,
                "username": "vipshock",
                "image": {
                    "id": 23,
                    "original_image_url": "http://localhost/storage/images/users/image.jpg",
                    "preview_image_url": "http://localhost/storage/images/users/image-scaled.jpg"
                }
            },
            "text": "Комментарий для сигмы",
            "images": [
                {
                    "id": 24,
                    "original_image_url": "http://localhost/storage/images/comments/image.jpg",
                    "preview_image_url": "http://localhost/storage/images/comments/image-scaled.jpg"
                }
            ],
            "created_at": "2024-10-17T17:33:24.000000Z",
            "updated_at": "2024-10-17T17:33:24.000000Z"
        }
    ]
}
```

### Store comment

```
POST api/v1/{model}/{id}/comments
```

Где `model` — название модели (users, posts) и `id` — id пользователя

Создает новый комментарий модели.

Требует токен.

json:

```json
{
    "data": {
        "id": 5,
        "user": {
            "id": 1,
            "username": "vipshock",
            "image": {
                "id": 23,
                "original_image_url": "http://localhost/storage/images/users/image.jpg",
                "preview_image_url": "http://localhost/storage/images/users/image-scaled.jpg"
            }
        },
        "text": "Комментарий для сигмы",
        "images": [
            {
                "id": 24,
                "original_image_url": "http://localhost/storage/images/comments/image.jpg",
                "preview_image_url": "http://localhost/storage/images/comments/image-scaled.jpg"
            }
        ],
        "created_at": "2024-10-17T17:33:24.000000Z",
        "updated_at": "2024-10-17T17:33:24.000000Z"
    }
}
```

### Delete comment

```
DELETE api/v1/{model}/{id}/comments/{commentId}
```

Где `model` — название модели (users, posts), `id` — id пользователя `commentId` — id комментария

Удаляет комментарий пользователя.

Требует токен.

Возвращает статус 204.

# Установка

* Склонировать проект
* Войти в созданную папку и ввести команду в терминал:

```
docker run --rm --interactive --tty -v $(pwd):/app composer install
```

* Создать .env файл на основе ```.env.example``` и настроить окружение. (Указать наименование бд, пользователя, пароль и
  т.д);
* Запустить докер контейнер командой:

```
sail up -d
```

* Войти внутрь контейнера:

```
docker exec -it biru-posts-php-1 bash
```

* Ввести команду

```
php artisan key:generate
```

* Запустить миграции

```
php artisan migrate
```

* Опробовать API;

## Дополнительная информация

* В проекте использутеся фреймворк для тестирования (PEST) и написаны несколько тестов;
* Наличие фиксера стилей (Pint);
* Проверка кода (Larastan);

Все вышеперечисленное проверяется в тестах github actions
