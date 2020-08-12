# Movies API

This project was generated with Symfony version 5.1.*

## How to Setup


clone the project
 
 to create database run
 
` php bin/console doctrine:database:create
` 

then to migrate tables run
`
 php bin/console doctrine:migrations:migrate  --no-interaction
`

to add fake data run 
`php bin/console doctrine:fixtures:load -n`

**note it takes about 2 minutes to add 100,000 row in database**

to run test
`php ./bin/phpunit
`

###now you are ready to dive into api

to start server run
`symfony server:start`



to overtime API

 API  `GET`  `http://127.0.0.1:8000/overtime/1??start_date=2020-07-25&end_date=2020-07-28`
you can change dates starting two years ago till now
and hotel id from hotels added to database

to benchmark API

 API  `GET`  `http://127.0.0.1:8000/benchmark/50??start_date=2020-07-25&end_date=2020-07-28`
you can change dates starting two years ago till now
and hotel id from hotels added to database

