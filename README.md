# ESB
Демонстрация подключения TWIG к фреймворку

К данному фреймворку возможно подключить любую библиотеку имеющую атомарную структуру (не ссылающуюся на различные внутренние сервисы) 

Документация Twig https://twig.symfony.com/

Контроллер для проверки подключения расположен:
* /controllers/Test.controller.php

Вызывается сервис для проверки: http://localhost/Test/Jonny

И twig преобразует шаблон:
 - Hello {{ name }} {% include "template" %}

в текст:
 - Hello Jonny I'm from TEMPLATE