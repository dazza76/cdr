<?php

// TODO Задача 1 [Выполнено]: Thursday, December 06, 2012 5:20 PM
/*
Высылаю Вам новую порцию =)
Есть дамп базы (так одна табличка старая, новую пока не реализовали вживую), пример нашей версии и описание полей.
Хотелось бы получить реализацию
http://demo.line24.ru/cc-line24/admin.php/reports/cc
http://demo.line24.ru/cc-line24/admin.php/reports/hours
http://demo.line24.ru/cc-line24/admin.php/reports/queues

+ графики оттуда же
+ страничка с добавлением новых операторов (queue_agents)
+ авторизация на сессиях, два уровня – пользователь (только отчеты) и администратор.
 */

// TODO Задача 2: Sippeers. Это форма на добавление. Нужно из нее еще на редактирование и вывод списка сделать. Соответственно, все это идет в настройки, вместе с операторами.


// TODO Задача 3 [Выполнено]: Friday, February 22, 2013 3:56 AM
/*
1) Нужна галочка "Только мобильные" в записи разгвооров и статистике. По ней
выбираем ТОЛЬКО входящие вида "9ХХХХХХХХХ" и исходящие вида
"[9]89XXXXXXXXX".
2) В добавлении операторов не нужно поле "Телефон, на котором работает", с
ним работает только Asterisk.
3) В настройках нужно разнести по пунктам: "Операторы", "Очереди" (таблицу
скину позже) и "Расписание" (здесь будет расписание диспетчеров, пока в
зачаточном состоянии, мы еще алгоритм разрабатываем)
 */

// TODO Задача 4: Управления очередями. docs/task5  Friday, February 22, 2013 3:56 AM
/*
Дамп таблицы для отчета дамп для управления очередями шлю.
По второй табличке все более-менее просто:
Имя очереди, Интерфейс (в скобках -- устройство, у нас будет иметь вид
ХХХХ(SIP/YYYY)), где ХХХХ -- номер оператора, а УУУУ -- 3-4значный номер
телефона, на котором он работает. Пенальти -- грубо говоря уровень скилла
оператора, uniqueid -- не используется, но астериск его хотел, туда можно
md5 вклинивать, главное, чтобы был уникален. Paused -- 0 или 1, в
зависимости от того, поступают ли вызовы агенту.
---------------------------
 */

// TODO Задача 5: Супервизоры. docs/task5
/*
1) В записи Tab1 и Tab2 обозвать "Звонки" и "Автоинформатор"
2) В записи тоже нужен вывод фамилии оператора.
3) В настройках по операторам доделать редактирование и удаление.
4) Шапки таблиц в записи и очереди необходимо закрепить, чтобы они не
уползали при прокрутке.
5) Панельки для супервизора прилагаются.


 Дима, а сможете сегодня две формочки сделать? Образец прикладываю.
По супервизор 1:
Очереди -- все, на каждую по строчке.
Операторы -- суммарное количество операторов в них. Можно брать из базы.
Ожидают -- количество вызовов в ней в таблице ActiveCall.
Дольше всего ожидает -- максимальное время ожидания на данный момент.
Обслужено -- суммарное число принятых и переведенных звонков. либо с 00:00,
либо за последние 24 часа. Задается в конфиге.
Уровень обслуживания -- процент вызовов, обслуженных за заданное время
(задается в самой форме). Во время обслуживания тут входит ожидание и
разговор.

По второй: все в файле вроде расписано.

Ссылки на формы в шапку под названием "Супервизору".
---------------------------
 */
?>
<html>
<body>
	<h1 align="center">IP-АТС ИКТ Asterisk</h1>
    <h3 align="center"><span style="color: #FF0000;" >требуеться модуль апача <i>mod_rewrite</i></span></h3>
    <h2 align="center">
        <a href="cdr">Запись разговоров</a><br />
        <a href="queue">Очередь</a><br />
        <a href="timeman">Длительности ожидания и разговора</a><br />
        <a href="settings">Настройки</a><br />
    </h2>
</body>
</html>
