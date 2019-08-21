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
1. В директории <code>{%project_path%}/application</code> запустить команду: <code>composer install</code>
2. В директории <code>{%project_path%}/</code> запустить команду: <code>docker-compose build</code>
3. Затем в этой же директории <code>docker-compose up -d</code>
4. Теперь нужно подготовить consumer для rabbitMQ (Не смог вшить скрипт в docker-compose, потому-что Rabbit запускается после php-fpm)
5. <code>docker exec -it test_php-fpm_1 bash</code>
6. <code>./bin/console messenger:consume -vv</code>

Готово: 

<code>http://192.168.99.100/</code> - on docker-machine (like for Windows 10 Home)

<code>http://localhost/</code> - on normal docker
