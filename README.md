# Tree-builder

## Installation

### Pre-requisites
- `PHP >=7.0`
- `Composer`
- `npm`
- `MySQL`

### Step 1: Setup MySQL configuration

- Copy `.env.example` file to `.env`
    ```shell
    cp .env.example .env
    ```
- Change `db_user`, `db_password` and `db_name` in `.env` file

### Step 2: Execute database migrations

- Create database
    ```shell
    php bin/console doctrine:database:create
    ```
- Create tables and mock tree
    ```shell
    php bin/console doctrine:migrations:migrate
    ```

### Step 3: Install packages and build
- Install `composer` and `node` packages
    ```shell
    composer install
    npm install
    ```
- Build `Vue` application
    ```shell
    npm run build
    ```
### Step 4: Run application

- Run following command and open [http://localhost:8000](http://localhost:8000)
    ```shell
    php bin/console server:run
    ```
