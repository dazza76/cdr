=================
 CDR
=================

--------------------
 Task Documentation
--------------------

# Task 1. cdr
Прикладываю файлик, он работает, но долго и неудобно) Включаемые скрипты – календарик и список пользователей. Помимо этого – дамп базы.

По выводу:

- Вывод всех записей из БД, для которых в `%DOCUMENT_ROOT%/monitor` есть файл с записью.
- Фильтры по номеру, с которого звонили; номеру, на который звонили; времени звонки; направлению звонка (входящий/исходящий); цифровому коду оператора.
- Постраничный вывод (к примеру, по 50 записей на страницу, можно меняемое количество, можно фиксированное)


Желательно на JavaScript/AJAX с тем, чтобы не перезагружать каждый раз страницу и можно было применить эффекты. В общем, как бы тупо не звучало, но «сделать красиво».

По базе:
- **Calldate** – дата и время вызова
- **Clid** – Так называемый CALLERID (Имя в кавычках, номер в скобках). Выводится на телефон, по факту в самой странице не используется
- **Src** – номер, с которого звонили.
- **Dst** – набранный номер.
- **Dcontext** – контекст (служебная вещь asterisk). По сути, если incoming – то вызов входящий, если остальные – то исходящий. Исключение – контексты вида `from-**`, служащие для связи с другими станциями. Там придется по длине номера `dst` смотреть, если 3-4 цифры, то вызов внутренний, если больше – он в город, соответственно, при наличии записи разговора выводим.
- **Channel** – имя канала (не используется в отчете, служебная информация)
- **Dstchannel** – то же самое.
- **LastApp** – на какой команде закончился вызов, нам не интересно.
- **LastData** – параметры, переданные последней команде.
- **Duration** – длительность вызова.
- **Billsec** – сколько времени вызов был отвечен
- **Disposition** – состояние (занято, нет ответа, принят). Нам нужны только ANSWERED (неприянтые или занятые естественно не записываются)
- **Amaflags** – не используется, стандартное поле.
- **Accountcode** – используется
- **Uniqueid** – идентификатор вызова (под этим имененем храним файл с записью)
- **Userfield** – пользовательское поле, в случае исходящего вызова там может быть записан код оператора. Нам достаточно, если Вы выведете этот код. В случае входящего код принявшего вызов оператора лежит в поле `dstchannel`.

В выводе должны быть следующие данные:

- Направление (можно красивыми стрелочками) – входящий или исходящий.
- Дата и время
- Источник (вызывающий абонент)
- Назначение (вызываемый абонент)
- Оператор (в описании поля 16 я уже расписал, где брать его код).
- Длительность
- Ссылка на запись или флеш/яваскрипт плеер с записью (если плеер, то желательно сделать кнопку для скачивания)
- Поле «Комментарий». В базе можно создать еще поле для него, соответственно, при желании без обновления страницы можно добавить какой-либо комментарий, либо изменить уже существующий.

Как пример системы – http://demo.line24.ru.

# Task 2. queues
Высылаю Вам новую порцию =)
Есть дамп базы (так одна табличка старая, новую пока не реализовали вживую), пример нашей версии и описание полей.
Хотелось бы получить реализацию

- http://demo.line24.ru/cc-line24/admin.php/reports/cc
- http://demo.line24.ru/cc-line24/admin.php/reports/hours
- http://demo.line24.ru/cc-line24/admin.php/reports/queues

- графики оттуда же
- страничка с добавлением новых операторов (queue_agents)
- авторизация на сессиях, два уровня – пользователь (только отчеты) и администратор.

## Task 2.2
- Подписи в сравнении: вместо chart написать сравнение «выбраный промежуток и выбранный промежуток» Y-values написать количество. Данной сравнение вроде ок. идем дальше.
- По очередям – было да, но в ближайшее заканчиваем переносить все на базу. Дамп таблицы пришлю в ближайшее время.
- По операторам. Предсказать сложно, среднее – 10. Но все зависит от клиента, будем рассчитывать на 100. Таблица хранит текущий штат, но уволенных не всегда из нее удаляют. Плюс некоторые редактируют записи про уволенных для использования их новыми операторами. Так что все сложно и запутанно. Сама АТС позволяет авторизоваться любому оператору, чья запись есть в данной таблице. Лучше все-таки хранить информацию о всех операторах с тем, чтобы в статистике однозначно было видно, кто обрабатывал тот или иной вызов. Насколько критичен выигрыш во времени в случае использования второго варианта?

## Task 2.3 Дополнения
- Нужна галочка "Только мобильные" в записи разгвооров и статистике. По ней выбираем ТОЛЬКО входящие вида `9ХХХХХХХХХ` и исходящие вида
`[9]89XXXXXXXXX`.
- В добавлении операторов не нужно поле "Телефон, на котором работает", с ним работает только `Asterisk`.
- В настройках нужно разнести по пунктам: "Операторы", "Очереди" (таблицу скину позже) и "Расписание" (здесь будет расписание диспетчеров, пока в зачаточном состоянии, мы еще алгоритм разрабатываем)
- И забыл еще, в суточном, месячном и недельном отчетах фильтр по очереди сделать надо бы.

- Дим, вызовы в контекстах dialout callback или autoinform выводить не надо.

- Дима, в отчете по очередям не пашет выборка по самим очередям. Они имеют вид 039Х, это критично? Профиль вызовов пашет.

# Task 3. sippeers
Это форма на добавление. Нужно из нее еще на редактирование и вывод списка сделать. Соответственно, все это идет в настройки, вместе с операторами.
...Не совсем, sippeers к очередям не имеет отношения. В ней параметры учетных записей для SIP-клиентов, т.е. телефонов, софтфонов etc. Так же скрипт на добавление, простенькая формочка.

Прилагаю два дампа, оба рабочих, таблицу по полям (`main` – основные настройки, `expert` – расширенные, нужно будет скрыть их под спойлером, `hidden` – выводим, поля обновляются asterisk).

  Docs: /sippeers


# Task 4

SELECT MIN(NOW()-datetime) FROM ((SELECT datetime,action FROM `agent_log`  WHERE agentid = 1024 AND action IN ('Login','unpause','unaftercal') ORDER BY `agent_log`.`datetime` DESC LIMIT 1) UNION ALL (SELECT timestamp as datetime,status AS action FROM call_status WHERE memberId=1024 AND status LIKE 'COMPLETE%' ORDER BY timestamp DESC LIMIT 1)) AS temp;

Проверить индексирование записей (одновременно сличать и автоифнорматор, и записи разговоров)

Включить автообновление и сортировки где возможно.

Расписание (Вместо ставки делаем факт, считаем суммарную длительность как в месячном отчете) + экспорт в xls.

Спиздить отчет по рабочему времени из лайна + выделение строк по клику другим цветом.

Настрйока очередей.

Настройка автоинформатора

Разные перерывы (пользовательские)


