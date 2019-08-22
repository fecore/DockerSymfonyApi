# Простой REST API

## Роуты ##
Пути, с помощью которых можно управлять API

Принимают объект-JSON методами POST и PUT

- GET / - Показать все items

- POST / - Создать новою item (Пример параметров (JSON): {"name": "Test", "onActive":1})

- PUT /1 - Обновить item 1 (Пример параметров (JSON): {"name": "Test", "onActive":1})

- DELETE /1 - Удалить item 1

## Описание ##

Любые изменения сущности Item будут записываются в файл: <code>/application/storage/items.txt</code>

Там хранятся только активные записи

Для записей в файл был использован RabbitMQ


## Установка ##

0. Распаковать архив
1. В директории <code>{%project_path%}/application</code> запустить команду: <code>composer install</code> (Можно также выполнить на стороне сервера после "docker up")
2. В директории <code>{%project_path%}/</code> запустить команды: <code>docker-compose build</code> и <code>docker-compose up -d</code>
4. Теперь нужно подготовить consumer для rabbitMQ (Не смог вшить скрипт в docker-compose, потому-что Rabbit запускается после php-fpm)
5. <code>docker exec -it НАЗВАНИЕ_КОНТЕЙНЕРА_ДЛЯ_PHP_FPM bash</code> (для входа на php-fpm сервер и запуска consume)
6. <code>./bin/console doctrine:migrations:migrate</code>
7. <code>./bin/console messenger:consume -vv</code>


Готово: 

<code>http://192.168.99.100/</code> - on docker-machine (like for Windows 10 Home)

<code>http://localhost/</code> - on normal docker
