# ESB

## Правила развертки
* сделать клон ветки, запустить из командной строки
  * git clone https://github.com/aatk/ESB.git && cd ESB
* Веб сервер настроить на папку ESB, куда склонировалась ветка
* Переименовать все файлы ht.access на .htaccess в папках 
  - /models/settings/ht.access
  - /ht.access
* Запустить на веб сервере (localhost или по настройке apache) http://localhost/System/updatesystem (Обновление системы, первоначальная настройка)

## Подключение БД
* Создать базу данных "demo" на сервере localhost
* Пользователя MySql с логином и паролем "demo" с правами на изменение БД 
  * изменить данные подключения к БД можно в файле /models/settings/connectionInfo.json
