<?php
// @codingStandardsIgnoreFile
// @codeCoverageIgnoreStart
// this is an autogenerated file - do not edit
spl_autoload_register(
    function($class) {
        static $classes = null;
        if ($classes === null) {
            $classes = array(
                'acarrayobject' => '/ac/base/ACObject.php',
                'acconsole' => '/ac/utils/ACConsole.php',
                'acdataobject' => '/ac/base/ACObject.php',
                'acdatetime' => '/ac/utils/ACDateTime.php',
                'acdbbasecommand' => '/ac/db/command/ACDbBaseCommand.php',
                'acdbcommand' => '/ac/db/ACDbCommand.php',
                'acdbconnection' => '/ac/db/ACDbConnection.php',
                'acdbconnectionexception' => '/ac/db/ACDbConnection.php',
                'acdbdeletecommand' => '/ac/db/command/ACDbDeleteCommand.php',
                'acdbinsertcommand' => '/ac/db/command/ACDbInsertCommand.php',
                'acdblockcommand' => '/ac/db/command/ACDbLockCommand.php',
                'acdbresult' => '/ac/db/ACDbResult.php',
                'acdbselectcommand' => '/ac/db/command/ACDbSelectCommand.php',
                'acdbsqlexception' => '/ac/db/ACDbConnection.php',
                'acdbunlockcommand' => '/ac/db/command/ACDbLockCommand.php',
                'acdbupdatecommand' => '/ac/db/command/ACDbUpdateCommand.php',
                'acdbwherecommand' => '/ac/db/command/ACDbBaseCommand.php',
                'acexception' => '/ac/base/ACException.php',
                'achtml' => '/ac/web/helper/ACHtml.php',
                'acjavascript' => '/ac/web/helper/ACJavaScript.php',
                'acjson' => '/ac/web/helper/ACJSON.php',
                'aclistobject' => '/ac/base/ACObject.php',
                'acloader' => '/ac/base/ACLoader.php',
                'acobject' => '/ac/base/ACObject.php',
                'acpagenator' => '/ac/web/helper/ACPagenator.php',
                'acpropertyvalue' => '/ac/utils/ACPropertyValue.php',
                'acrequest' => '/ac/web/ACRequest.php',
                'acresponse' => '/ac/web/ACResponse.php',
                'acsession' => '/ac/web/ACSession.php',
                'acsingletonexception' => '/ac/base/ACException.php',
                'activecall' => '/model/ActiveCall.php',
                'acutils' => '/ac/utils/ACUtils.php',
                'acvalidation' => '/ac/utils/ACValidation.php',
                'acvardumper' => '/ac/utils/ACVarDumper.php',
                'acxml' => '/ac/web/helper/ACXML.php',
                'agentlog' => '/model/AgentLog.php',
                'app' => '/Application.php',
                'application' => '/Application.php',
                'auth' => '/model/Auth.php',
                'autodialout' => '/model/Autodialout.php',
                'autoinformcontroller' => '/controller/AutoinformController.php',
                'callstatus' => '/model/CallStatus.php',
                'cdr' => '/model/Cdr.php',
                'cdrcontroller' => '/controller/CdrController.php',
                'controller' => '/controller/component/Controller.php',
                'export' => '/controller/component/Export.php',
                'filtersvalue' => '/controller/component/FiltersValue.php',
                'log' => '/ac/logger/Log.php',
                'model' => '/model/Model.php',
                'operatorcontroller' => '/controller/OperatorController.php',
                'outgoingcontroller' => '/controller/OutgoingController.php',
                'queue' => '/model/Queue.php',
                'queueagent' => '/model/QueueAgent.php',
                'queuecontroller' => '/controller/QueueController.php',
                'schedulesettingscontroller' => '/controller/settings/ScheduleSettingsController.php',
                'settingscontroller' => '/controller/SettingsController.php',
                'sippeer' => '/model/Sippeer.php',
                'supervisorcontroller' => '/controller/SupervisorController.php',
                'timemancontroller' => '/controller/TimemanController.php',
                'user' => '/model/User.php',
                'utils' => '/Utils.php'
            );
        }
        $cn = strtolower($class);
        if (isset($classes[$cn])) {
            require __DIR__ . $classes[$cn];
        }
    }
);
// @codeCoverageIgnoreEnd