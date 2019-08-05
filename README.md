# REST JSON API for Lamoda [![Build Status](https://travis-ci.org/utz0r2/rest-json-api-lamoda.svg?branch=master)](https://travis-ci.org/utz0r2/rest-json-api-lamoda)


## Overview

Simple REST JSON API for Lamoda without framework. API can get all containers, one container and get container with only unique products.
Docker running with Nginx, PHP-FPM, Composer and Mongodb.

![Main Screen](https://raw.githubusercontent.com/utz0r2/rest-json-api-lamoda/master/screenshots/main.png)

___

## Installing and start 

To install Git, download it and install following the instructions :

```sh
git clone https://github.com/utz0r2/rest-json-api-lamoda.git
```

Go to the project directory :

```sh
cd rest-json-api-lamoda
```

Start the application :

```sh
sudo docker-compose up
```

Open your favorite browser :

* [http://localhost](http://localhost/)

Generate some containers and check API =)

___

Stop and clear services

```sh
sudo docker-compose down -v
```

___

### Testing PHP application with PHPUnit

```sh
docker-compose exec -T php ./vendor/bin/phpunit
```

___

## About me
Hello, my name is Igor Veselov. I am Senior Full Stack Web Developer. Main specialization - Ecommerce websites. Opened for interesting offers.

___

## Contacts
- SKYPE: [utz0r2](skype:utz0r2)
- EMAIL: [dev@xfor.top](mailto:dev@xfor.top)
- WWW: https://xfor.top/
- LinkedIn: https://www.linkedin.com/in/igor-veselov/
- github: https://github.com/utz0r2
