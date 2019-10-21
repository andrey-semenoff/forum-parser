# forum-parser
PHP parser for forums

### Project is consist of 2 parts:
1) Application (/app folder) - logging into account on forum & parse forum's pages. Each paresd message from page it send to RabbitMQ server.
2) Service worker (/service folder) - listen to RabbitMQ queue, prepare PostgreDB and store messages got from channel

### Installation:
- install RabbitMQ (https://www.rabbitmq.com/download.html). It requires Erland, so your can get it here (https://www.erlang.org/downloads)
- ensure that RabbitMQ process is working
- not necessary, but really useful is RabbitMQ UI management (https://www.rabbitmq.com/management.html) for watching status of connections and queues
- clone this repo
- run in each - /app & /service folders
```sh
composer install 
```
- go to /app/config & /service/config and rename file `config.example.php` -> `config.php`, then set your settings and credentials
- run PostgresDB server & create DB for this application (set the name of this database into `/service/config/config.php`)
- open terminal and go to `/service` folder. Run 
```sh 
php index.php
``` 
or start php-server using 
```sh
php -S localhost:<port> 
```
If all works fine, you will see log message from service worker. You can open any number of terminals and run service workers - all of them will get messages from RabbitMQ in queue.
- open new terminal and go to `/app` folder. Run 
```sh
php index.php
```
or start php-server like on previous step. If all works fine you will see:
  1) result of login attempt to forum
  2) parsed messages from forum's pages. These messages are sending to RabbitMQ, then service workers will get them, and store into PostgresDB using stored functions.
- if after opening of terminal you will get exception like `"Fatal error: Uncaught Error: Class 'Core\ForumoduaProducer' not found"`, then please run 
```sh
composer dump-autoload -o
```
and try again. Sometimes php don't see updates in autoload files, so try to reopen terminal and run index.php again.
