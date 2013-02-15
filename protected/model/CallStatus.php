<?php
/**
 * CallStatus class  - CallStatus.php file
 *
 * @author     Tyurin D. <fobia3d@gmail.com>
 * @copyright  (c) 2013, AC
 */

/**
 * CallStatus class
 *
 * @property string $callId            - [0] идентификатор вызова
 * @property string $callerId          - [1] номер, с которого звонили
 * @property string $memberId          - [2] номер оператора
 * @property string $status            - [3] состояние вызова
 * @property string $timestamp         - [4] время последнего изменения
 * @property string $queue             - [5] очередь
 * @property string $position          - [6] позиция в очереди
 * @property string $originalPosition  - [7] начальная позиция в очереди
 * @property string $holdtime          - [8] время ожидания
 * @property string $keyPressed        - [9] кнопка, по которой абонент вышел из очереди (мы не используем, посему при переводе пишем сюда, на какой номер перевели звонок)
 * @property string $callduration      - [10] длительность разговора
 * @property string $ringtime          - [11] время поднятия трубки
 *
 * @property string $priorityId
 */
class CallStatus extends ACDataObject {

    const TABLE            = "call_status";
    const ACT_COMPLETE     = 'complete';
    const ACT_ABANDONED    = 'abandoned';
    const ACT_AVGCOMPLETE  = 'avgcomplete';
    const ACT_AVGABANDONED = 'avgabandoned';

    /**
     * Форматированный номер с которого звонили
     * @return string
     */
    public function getCaller() {
        $vip = ($this->priorityId) ? '(VIP №' . $this->priorityId . ')' : '';
        return html($this->callerId . " " . $vip);
    }

    /**
     * Оператор
     * @return string
     */
    public function getOper() {
        $num = $this->memberId;
        if (substr($num, 0, 4) == 'SIP/') {
            $str = 'Телефон ' . substr($num, 4, 4);
        } else {
            $str = QueueAgents::getOper(substr($num, 0, 7));
        }

        return html($str);
    }

    /**
     * Текстовое состояние вызова
     * @return string
     */
    public function getStatus() {
        switch ($this->status) {
            case 'ABANDON': return 'Не дождался';
            case 'COMPLETEAGENT': return 'Завершен оператором';
            case 'COMPLETECALLER': return 'Завершен клиентом';
            case 'TRANSFER': return 'Переведен на ' . $this->keyPressed;
        }
    }


}