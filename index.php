<?php

// TODO Task 1 [Done]: Thursday, December 06, 2012 5:20 PM
/* ------------------------------------------------ ----------------------------
 * I send you a new batch =)
 * There is a database dump (as one plate is old, not yet implemented the new live)
 * Example of our version, and description fields.
 *
 * I would like to get implementation
 * Http://demo.line24.ru/cc-line24/admin.php/reports/cc
 * Http://demo.line24.ru/cc-line24/admin.php/reports/hours
 * Http://demo.line24.ru/cc-line24/admin.php/reports/queues
 *
 * + Graphics from the same
 * Page with the addition of new operators (queue_agents)
 * + Authentication sessions on two levels - the user (only reports) and the administrator.
 -------------------------------------------------- -------------------------- */

// TODO Task 2: Sippeers.
/*
 * This form is to add. Was still a need for editing and output the list to do.
 * Accordingly, it all goes to the settings, together with operators.
 * Not really, sippeers to queues is irrelevant. In her account settings
 * Records for SIP-clients, ie phones, softphones etc.
 */
/*
 * Docs: / sippeers
 * I enclose two dump both the workers in the fields table (main - main settings, expert - advanced, you will need to hide them under spoiler, hidden - deduce fields are updated asterisk).
 * The same script to add, a simple shaper.
 */

// TODO Task 3 [Done]: Friday, February 22, 2013 3:56 AM
/* ------------------------------------------------ ----------------------------
 * + 1) Need checkbox "Only mobile" in razgvoorov records and statistics. On it
 * Choose only the incoming type "9HHHHHHHHH" and outgoing type "[9] 89XXXXXXXXX".
 + * 2) In addition the operators do not need the "phone that runs" with
 * It only works Asterisk.
 * + 3) In the settings should be moved by point: "Operators", "Queue" (table
 * Strip off later) and "Schedule" (here is the schedule controllers, until
 * Infancy, we are developing an algorithm)
 -------------------------------------------------- -------------------------- * /

// TODO Task 4: Office bursts. docs/task5 Friday, February 22, 2013 3:56 AM
/*
 * Dump the table to dump the report to manage queues whore.
 * On the second plate is more or less simple:
 * Name of the queue interface (in brackets - device, we will have the form
 * XXXX (SIP / YYYY)), where XXXX - the number of the operator, and Oooo - 3-4-digit number
 * Phone on which he works. Penalty - roughly the level of skill
 * Operator, uniqueid - not used, but it would asterisk, there can be
 * Md5 wedge in, the main thing that was unique. Paused - 0 or 1,
 * Depending on whether the received calls to the agent.
 */

// TODO Task 5: The supervisor. docs/task5
/*
 * + 1) Recorded Tab1 and Tab2 call "Call" and "Answering machine"
 + * 2) In the recording, too, need the output name of the operator.
 * + 3) In the setting of operators to finish editing and deleting.
 * + 4) Hats tables in the record and the queue must be secured so that they do not
 * Crawled when scrolling.
 * 5 +) socket for a supervisor attached.
 *
 *
 * Queues - all on each line.
 * Operators - the total number of agents in them. Can be taken from the base.
 * Waiting - the number of calls to her table ActiveCall.
 * The longest waits - the maximum waiting time at the moment.
 * Handle - the total number of calls received and translated. or 00:00,
 * Or for the last 24 hours. Specified in the config file.
 * The level of service - the percentage of calls served within the specified time
 * (Specified in the form itself). During the service there is
 * Waiting and talking.
 */

// TODO Task 6: Supervisor - Improvements
/*
 * 1) The dynamic update supervisors.
 * 2) Time counters must tick, after all.
 * 3) The formula Service Level: Service Level,% = Answered Less X seconds / Entered
 * SL = (number of a holdtime <X) / total number of. X IN ('10 ', '20', '30 ') - the choice of the filter.
 *
 * The parameters - you need a conclusion either from the beginning of the day, or for the last half an hour through the radio.
 * One at a time - in the table queues1-3.
 * Time - by operlog (agent_log table.)
 *
 * 4) in the panel supervisor do "Distribution", there is entered
 * Distribution of incoming calls to numbers.
 * 5) Context - preferably in the config file, but usually it's everywhere incoming.
 * 6) In the "Operators" only show registered and serving
 * Queue specified in the config file.
 * 7) In the settings do another plug - "Pause".
 */


/*
logag look at what filters it receives and from where
session parametr 1 - indicates that the filters are taken from the session
[1:34:58] Dmitry: sabmite steranes all previous filters, and the currently selected setting new
[1:35:23] Dmitry: all filters sbivayutsya with any het request, except the section
[1:35:56] Dmitry: it is not the reason for yavlyaetsya zbrosa of session

*/


/*
1. [+] The date format tables EVERYWHERE cause DD.MM.YYYY HH: MM: SS
2. [+ -] Tick "Mobile" in a report by one
      > Besides tables. Table does not respond to the filters «VIP» and "mobile"
3. [+] Check Sunday in the weekly report. According to debug it is clear that he is looking to 00:00 Sunday, ignoring him.
4. [-] Check layout (table continues to creep)
      > Instant solution can serve as a rejection of the fixity of the cap at the top of the table (because of her all palzet)
      > Another way is to CSS + JS, - take an indefinite period, as I myself will have to dig in the internet and read docks
5. [+] Recorded deduce the duration of the file, not the data from the database - http://codepaste.ru/1358/
6. [+ -] Reports operators - Efficiency (I called it monthly) + Working time, as http://demo.line24.ru
       > Need to check
7. [+] Replace the sliders in the calendars on the input field.
8. [+] The tick «VIP» to record conversations
9. [=] Sammari plate (as in any report) in graphical reports (daily, weekly, monthly) under the graph
10. [+ -] Ticks "Mobile», «VIP» in profile calls
      > VIP check
11. [-] The report supervisor operators in the table of operators in the graph line to write the name of the queue in a column
12. [-] The report supervisor operators must go time the agent spent in this status
13. [+ -] In the queue does not work check «VIP». Not recalculated table, not output records (in the callerid added "98" in the beginning of the number)
14. [-] In the setting of the queue table that is not used.
15. [-] No setting SIP peers
16. [?] Does not work report on an answerphone (ignores configuration, no display)
17. [?] Not record conversations "Answering machine"
18. [-] Menus creeps even at 1280x1024. It makes sense to move the sub down to make a second strip.
19. [-] Operators in the settings it makes sense to print page by page.
20. [-] In the setting of the operators should make the filter at a time, search by name, code operator.
21. [?] In the setting of the operators while keeping the script runs once a long time.
22. [-] Operators to report Supervisor operators select only those whose state <> 'out'
23. [+ -] Button "Export to Excel»
      > Until ready to "Profile Call", "Supervisor: Distribution", "Monthly Report"
 */
// 1. We had no time will be displayed columns for "processing" or "call."

/*
Thus, in the system:
1) Record.
There is no record of conversations answerphone. They just do not appear.
2) Queue.
No sammari in graphs
In no particular statement is not recalculated when choosing a tablet only mobile
I can not open a week and compared. Debug is also not open.
3) Profile.
When you export a file opens twice
In the file write iconv ('utf8', 'cp1251', $ data);
4) Supervisors.
Not a list of queues.
No time is ticking.
"Busy" rename to "talk"
Not all status displays.
5) Setting the operator - the same joint, not the table.


4. + + + Check the layout (table continues to creep)
6. + + + Reports operators - Efficiency (I called it monthly) + Working time, as http://demo.line24.ru
8. + + + The tick «VIP» to record conversations
9. + + - Sammari plate (as in any report) in graphical reports (daily, weekly, monthly) under the graph
11. + + + In the report, the supervisor operators in the table of operators in the graph line to write the name of the queue in a column
12. --- The report supervisor operators must go time the agent spent in this status
13. + - + In the queue does not work check «VIP». Not recalculated table, not output records (in the callerid added "98" in the beginning of the number)
14. --- In the setting of the queue table that is not used.
15. + + + No setup SIP peers
16. --- Does not work report on an answerphone (ignores configuration, no display)
17. --- Not record conversations "Answering machine"
18. + + + Menus creeps even at 1280x1024. It makes sense to move the sub down to make a second strip.
19. + + + Operators in the settings it makes sense to print page by page.
20. + + + In the setting of the operators should make the filter at a time, search by name, code operator.
21. + + + In the setting of the operators while keeping the script runs once a long time. (Sozranyat it once, but do not redirect)
23. --- No list of queues that are not ticking time, not all status displays, change the "Busy" to "talk"
24. + - + Button "Export to Excel»



+ + + - Made
+ - + - Not the main (not enough data on the table VIP) / not completely.
----- Not done



Dump the table to dump the report to manage queues whore.

On the second plate is more or less simple:
Name of the queue interface (in brackets - device, we will have the form
XXXX (SIP / YYYY)), where XXXX - the number of the operator, and Oooo - 3-4-digit number
phone on which he works. Penalty - roughly the level of skill
operator, uniqueid - not used, but it would asterisk, there can be
md5 wedge in, the main thing that was unique. Paused - 0 or 1,
Depending on the agent receives a call.

I would like to understand the maturity when it is ready.

Send full Bild
[0:30:30] Vadim Tesalov: When some mobile clients empty schedule
[0:33:06] Vadim Tesalov: In avtoifnormatore ignore settings result /
[0:34:19] Vadim Tesalov: by pressing the "n attempts to" let the spoiler reveals the details
*/

/*
1) + + Let's repeat the cap periodically in the table (as in phpmyadmin, the findings for large cap repeated periodically as a row in the table)
2) - Autoinformlog.php not plow, maybe I tuplyu where to look?
3) + + Sliders can still replace windows?
4) + - In an answerphone need another option - the number of circumcised characters in the beginning of the number. It is very necessary =)
5) - In the Supervisors need the ability to customize the output of operators. Or ticks, or in the config.
        Operators of write status of "Talking", "freedom", "At half-time," "In Progress"
6) - Tick, remember, huh?)
7) + + In operlog want to have a filter on the actions ("challenges", "action")
8) + + Reports operators do not choose NONE. NONE of the request to remove the «memberId IN ('..', '..', 'NONE', '..', '..')»
*/

// ------------------------------------------------ ---------------------------
// TODO Task 7: Current
// TODO Task 7.1: Time
// SELECT MIN (NOW ()-datetime) FROM ((SELECT datetime, action FROM `agent_log` WHERE agentid = 1024 AND action IN ('Login', 'unpause', 'unaftercal') ORDER BY `agent_log`. `Datetime `DESC LIMIT 1) UNION ALL (SELECT timestamp as datetime, status AS action FROM call_status WHERE memberId = 1024 AND status LIKE 'COMPLETE%' ORDER BY timestamp DESC LIMIT 1)) AS temp;
// + Check indexing records (at the same time and compare avtoifnormator, and call recording)
// + Enable auto-update and sort where possible.
// TODO Task Schedule 7.3 (Instead of making bets fact, we consider the total duration as a monthly statement) + export to xls.
// TODO Task 7.4've stole a report on the working time of the lane + selection of lines by clicking a different color.
// TODO Task 7.5 settings should queues.
// Setup + answerphone
// TODO Task 7.7 Different breaks (user)


// ------------------------------------------------ ---------------------------
// Wed while lifting the handset, s
//
// + + Status must finish the report with the plus sign (instead aftercall and unaftercall), time in HH: MM: SS (in duration of interruptions)
// Time ticking away in the supervisor.
// + + If you choose to record conversations in 100 or 500 records per page, the pages do not switch
// + + The report Operators Workload check-cap (odinkaovyh two columns with different data)
// Log (here you are free to do whatever you want, but it is necessary to restrict access)!
//
// Print_r ($ _SERVER);

 require_once 'protected/bootstrap.php';

 $app = new Application();
//print_r($_SERVER)
//print_r($app);
// history.replaceState({page: 3}, "title 3", "?page=3");
App::location('cdr');

//echo $_SERVER["HTTP_HOST"];

// header("Location: http://" . $_SERVER["HTTP_HOST"] . "/{$page}?{$get}");



