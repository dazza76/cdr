<?php

/**
 * SuperVisor class
 *
 * @package		AC
 */
class SuperVisor {

    public function shell($params = null) {
        if ( ! is_array($params)) {
            $params = array();
        }
        $params = array_replace(App::Config()->supervisor, $params);


        $queue_arr = $params['queues'];
        if ( ! is_array($queue_arr)) {
            $queue_arr = array_keys(Queue::getQueueArr());
        }

        if ($params['shell_exec']) {
            $shell        = array();
            $shell_string = $params['shell'];
            foreach ($queue_arr as $queue) {
                $vars    = array('queue' => $queue);
                $shell[] = ACUtils::parseTemplateString($shell_string, $vars);
            }
            $shell   = implode(' && ', $shell);
            Log::trace($shell);
            if ($shell) {
                $result = shell_exec($shell);
            }
            return ($result) ? $result : '';
        } else {
            $result = include APPPATH . 'system/asterisk.php';
            return $result;
        }
    }
}