# Peach - Simplify your local development with Docker

[![Latest Version on Packagist](https://img.shields.io/packagist/v/ysocode/peach.svg?style=flat)](https://packagist.org/packages/ysocode/peach)
[![Downloads on Packagist](https://img.shields.io/packagist/dt/ysocode/peach.svg?style=flat)](https://packagist.org/packages/ysocode/peach)
[![License](https://img.shields.io/packagist/l/ysocode/peach)](https://packagist.org/packages/ysocode/peach)

## Introduction

Peach provides a local development experience based on Docker. No software or library needs to be installed locally
before using Peach. Peach's simple CLI allows you to start building your application without any previous experience
with Docker.

#### Inspiration

Peach is inspired by and derived from Sail, created by Taylor Otwell. For more information, check out
the [Sail repository](https://github.com/laravel/sail).

## Official Documentation

##### Install Peach using Composer:

```shell
composer require ysocode/peach --dev
```

##### Configure the services for Peach using the Basket Manager:

```shell
./vendor/bin/basket peach:install
```

##### Modify existing Peach services using the Basket Manager:

```shell
./vendor/bin/basket peach:add
```

##### Setting up a Shell Alias:

By default, Peach commands are invoked using the script **vendor/bin/peach**:

```shell
./vendor/bin/peach up
```

However, instead of typing `vendor/bin/peach` repeatedly to run Peach commands, you may want to set up a shell alias to
make running Peach commands easier:

```shell
alias peach="[ -f peach ] && sh peach || sh vendor/bin/peach"
```

##### Starting and Stopping Peach:

Before starting Peach, make sure no other web server or database is running on your local machine. To start all Docker
containers defined in your application's `docker-compose.yml` file, run the `up` command:

```shell
peach up
```

To start all Docker containers in the background, you can start Peach in "detached" mode:

```shell
peach up -d
```

Once the application's containers are up, you can access the project in your web browser at: http://localhost.

To stop all containers, you can press Control + C to interrupt the container's execution. If the containers are running
in the background, you can use the **stop** command:

```shell
peach stop
```

To restart the containers, you can use the **start** command:

```shell
peach start
```

To stop and remove all containers, you can use the **down** command:

```shell
peach down
```

## License

Peach is open-sourced software licensed under the [MIT license](LICENSE.md). 
