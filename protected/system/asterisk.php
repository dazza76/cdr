<?php
/*
1004 (SIP/2570) with penalty 50 (dynamic) (paused) (Not in use) has taken 1 calls (last was 10278 secs ago)
1002 (SIP/2588) with penalty 20 (dynamic) (paused) (In use) has taken 2 calls (last was 1258 secs ago)
1027 (SIP/2578) with penalty 10 (dynamic) (paused) (Not in use) has taken 38 calls (last was 3106 secs ago)
1003 (SIP/2581) with penalty 50 (dynamic) (paused) (On Hold) has taken 12 calls (last was 421 secs ago)
1021 (SIP/2585) with penalty 10 (dynamic) (Not in use) has taken 60 calls (last was 7 secs ago)
1022 (SIP/2508) with penalty 10 (dynamic) (paused) (Not in use) has taken 49 calls (last was 2003 secs ago)
1014 (SIP/2511) with penalty 10 (dynamic) (paused) (Not in use) has taken 15 calls (last was 411 secs ago)
1023 (SIP/2579) with penalty 10 (dynamic) (paused) (In use) has taken 1 calls (last was 15245 secs ago)
1011 (SIP/2515) with penalty 10 (dynamic) (Not in use) has taken 50 calls (last was 41 secs ago)
1013 (SIP/2505) with penalty 10 (dynamic) (In use) has taken 46 calls (last was 309 secs ago)
1006 (SIP/2586) with penalty 10 (dynamic) (paused) (Not in use) has taken 16 calls (last was 15171 secs ago)
1019 (SIP/2509) with penalty 10 (dynamic) (In use) has taken 43 calls (last was 215 secs ago)
1024 (SIP/2516) with penalty 10 (dynamic) (paused) (Not in use) has taken 33 calls (last was 800 secs ago)
1001 (SIP/2577) with penalty 50 (dynamic) (paused) (Not in use) has taken no calls yet
1005 (SIP/2513) with penalty 10 (dynamic) (paused) (Not in use) has taken 50 calls (last was 1979 secs ago)
1003 (SIP/2581) with penalty 10 (dynamic) (On Hold) has taken 12 calls (last was 421 secs ago)
1026 (SIP/2580) with penalty 10 (dynamic) (In use) has taken 4 calls (last was 2856 secs ago)
 */

$status_arr = array(
    'paused',
    'Not in use',
    'In use',
    'On Hold',
);
//$queue_arr = array_keys(Queue::getQueueArr());

$agents_result = App::Db()->createCommand()
        ->select('agentid')
        ->from(QueueAgent::TABLE)
        ->limit(10)
        ->order('RAND()')
        ->query();

$resut = array();
while ($row = $agents_result->fetchAssoc()) {
    $agentid = $row['agentid'];
    $status = $status_arr[rand(0, count($status_arr) - 1)];
    $queue = $queue_arr[rand(0, count($queue_arr) - 1)];
    $resut[] = "{$agentid} (SIP/{$queue}) with penalty 10 (dynamic) ({$status}) has taken 10 calls (last was 500 secs ago)";
}

return implode("\n", $resut);


