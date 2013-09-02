front-controller
================

Простая реализация шаблона Front Controller

#Установка

Устанавливается через composer.
Для использования нужно создать наследника от класса vollossy\FrontController\Controller, в котором переопределить
метод getClassName, а после вызвать метод run() в index.php
