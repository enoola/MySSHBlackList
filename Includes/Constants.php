<?Php

/*
 * here our constants...
*/

//define('CFG_AUTH_FILE','IN/auth.log');
//define('CFG_HOSTSALLOW_FILE', 'IN/hosts.allow');
//define('CFG_HOSTSDENY_FILE', 'IN/hosts.deny');
//define('CFG_LOG_FILE', 'LOG/MySSHBlackList.log');

define('CFG_AUTH_FILE','/var/log/auth.log');
define('CFG_HOSTSALLOW_FILE', '/etc/hosts.allow');
define('CFG_HOSTSDENY_FILE', '/etc/hosts.deny');
define('CFG_LOG_FILE', '/var/log/MySSHBlackList.log');

define('LOG_LEVEL_ERROR', 1);   //00000001
define('LOG_LEVEL_WARNING', 2); //00000010
define('LOG_LEVEL_VERBOSE', 4);//00000100
define('LOG_LEVEL_ALL', 7); //00000111

//define('AUTHORIZED_LOG_LEVEL', LOG_LEVEL_WARNING ^ LOG_LEVEL_ERROR);
define('AUTHORIZED_LOG_LEVEL', LOG_LEVEL_ALL);
define('NEWLINE', "\r\n");
define('INTERVAL_TO_LOOKUP', 60);
define('MAX_NUMBER_ATTACK', 5);
?>