# SoccerSphere

Simple web article all about soccer and football

## Requirement

1. Install Makefile
    - [Windows](https://gnuwin32.sourceforge.net/packages/make.htm)
    - Linux

        ```bash
        sudo apt install make
        ```

2. Install PHP
    - [Windows](https://windows.php.net/download#php-8.2)
    - [Linux](https://www.zend.com/blog/installing-php-linux)

## Getting Started

### Enviroment

1. Create .env file
2. Setting .env like .env.example

### Migrate Up and Seed

1. Migrate up to create table

    ```bash
    make migrate-up
    ```

2. Seed data from news.json

    ```bash
    make seed
    ```

### Run Server

```bash
make start port={8080}
```

### Migrate Down

```bash
make migrate-down
```
