# Product Catalogue

### Requirements

- PHP 5.6+
- npm 6+
- Mysql

### Run

Download the git repo 
`git@github.com:Njuman/product-catalogue.git`

#### Setup data connection
- Copy .env.exmaple to .env in the same folder.
- Edit credentials of the .env file

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT={port}
DB_DATABASE={database}
DB_USERNAME={username}
DB_PASSWORD={password}
```

#### Run Step.
- Run migrations `php artisan migrate`
- Run node server `npm run watch`
- Run app `php artisan serve`

#### Open app
- [localhost:8000](localhost:8000) here i'm using localhost because of cors same domain policy which i couldn't fix in this project.
- admin area [localhost:8000/admin](localhost:8000/admin)
