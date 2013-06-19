<?php
/**
 * ActiveCall class  - ActiveCall.php file
 *
 * @author     Tyurin D. <fobia3d@gmail.com>
 * @copyright  (c) 2013, AC
 */
/**
 * ActiveCall class
 *
 * @property string $callId            уникальный идентификатор вызова
 * @property string $callerId          номер, с которого пришел вызов
 * @property string $memberId          номер оператора, принявшего вызов
 * @property string $status            состояние вызова
 * @property string $timestamp         дата и время последнего изменения (поступил, принят и т.п.)
 * @property string $queue             название очереди, в которую пришел вызов
 * @property string $position          позиция в очереди
 * @property string $originalPosition  начальная позиция в очереди
 * @property string $holdtime          время ожидания ответа
 * @property string $keyPressed        не используется, здесь пишется кнопка, по которой человек вышел, если выход разрешен
 * @property string $callduration      длительность вызова
 * @property string $ringtime          время поднятия трубки
 */
class ActiveCall extends ACDataObject { 
        const TABLE = "ActiveCall";
}