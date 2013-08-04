SELECT SQL_CALC_FOUND_ROWS * FROM cdr
LEFT JOIN call_status ON `cdr`.`uniqueid` = LEFT(`call_status`.`callId`, CHAR_LENGTH(`cdr`.`uniqueid`))
WHERE file_exists > '0'
  AND dcontext NOT IN ( 'autoinform', 'outgoing', 'dialout' )
  AND calldate >= '2003-07-24 17:03:00' AND calldate <= '2013-07-25 17:03:00'
  AND NOT ( LEFT(`dcontext`, 4)='from' AND CHAR_LENGTH(`dst`)<=4 )
ORDER BY comment
LIMIT 0, 30



1353062433.4492


SELECT * FROM call_status
LIMIT 0, 30
