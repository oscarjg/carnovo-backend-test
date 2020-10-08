# Carnovo Backend Test

This project add some API Rest endpoints to:
* Fetch cars by criteria
* Fetch favorites cars by criteria
* Mark and un-mark favorite cars

The project Stack is:
* Symfony
* MongoDB
* RabbiMQ 

## Installation

##### Set env vars
Set your local env vars in .env.dev or .env.local file or use the default .env file is up to you.

##### Docker compose up
``` bash
> docker-compose up --build
```

##### Create fresh database
``` bash
> docker-compose exec php bin/console doctrine:mongodb:schema:create
```

##### Add a main user
``` bash
> docker-compose exec php bin/console doctrine:mongodb:fixtures:load --group installation --no-interaction
```

## Usage

### HTTTP Endpoints
##### Get user token

``` bash
> curl -XPOST -H 'Content-type: application/json' http://localhost:8080/api/login_check -d '{"username": "user", "password": "1234"}'
```
##### Get cars list example

``` bash
> curl -XGET -H 'Content-type: application/json' -H 'Authorization: Bearer <USER_TOKEN>' http://localhost:8080/api/cars?property=priceEU&order=desc&page=1&limit=10
```

##### Get favorite cars list example
``` bash
> curl -XGET -H 'Content-type: application/json' -H 'Authorization: Bearer <USER_TOKEN>' http://localhost:8080/api/cars/favorites?property=priceEU&order=desc&page=1&limit=10
```

##### Mark car as favorite example
``` bash
> curl -XPUT -H 'Content-type: application/json' -H 'Authorization: Bearer <USER_TOKEN>' http://localhost:8080/api/cars/favorites/mark -d '{"carId": "<CAR_ID>"}'
```

##### UnMark car as favorite example
``` bash
> curl -XPUT -H 'Content-type: application/json' -H 'Authorization: Bearer <USER_TOKEN>' http://localhost:8080/api/cars/favorites/unmark -d '{"carId": "<CAR_ID>"}'
```

### Inventory
##### Rabbit admin
http://localhost:15673

##### Update the inventory (consumer)
Open in a new terminal and execute the next command to create consumer queues
``` bash
> docker-compose exec php bin/console carnovo:inventory:rabbitmq:consumer -v
```

##### Send inventory to RabbitMQ (producer)
``` bash
> docker-compose exec php bin/console carnovo:inventory:file-to-rabbitmq --path /application/data.txt
```

### UNIT TESTS
To run inventory test
``` bash
> docker-compose exec php bin/phpunit
```


