<?Php


class tutu
{
    function __construct()
    {
	echo "tutu\r\n";
    }

}


/*

Feb 20 22:27:15 aust sshd[4322]: reverse mapping checking getaddrinfo for 122-146-40-72.adsl.static.sparqnet.net [122.146.40.72] failed - POSSIBLE BREAK-IN ATTEMPT!
Feb 20 22:27:15 aust sshd[4322]: pam_unix(sshd:auth): authentication failure; logname= uid=0 euid=0 tty=ssh ruser= rhost=122.146.40.72  user=root
Feb 20 22:27:15 aust sshd[4322]: pam_winbind(sshd:auth): getting password (0x00000388)
Feb 20 22:27:15 aust sshd[4322]: pam_winbind(sshd:auth): pam_get_item returned a password
Feb 20 22:27:15 aust sshd[4322]: pam_winbind(sshd:auth): request wbcLogonUser failed: WBC_ERR_AUTH_ERROR, PAM error: PAM_USER_UNKNOWN (10), NTSTATUS: NT_STATUS_NO_SUCH_USER, Error message was: No such user
Feb 20 22:27:17 aust sshd[4322]: Failed password for root from 122.146.40.72 port 59979 ssh2
*/
class tata extends tutu
{
	function __construct()
	{
		$subject[] = 'Feb 19 17:39:01 aust CRON[7539]: pam_unix(cron:session): session opened for user root by (uid=0)';
		$subject[] = 'Feb 20 22:27:15 aust sshd[4322]: reverse mapping checking getaddrinfo for 122-146-40-72.adsl.static.sparqnet.net [122.146.40.72] failed - POSSIBLE BREAK-IN ATTEMPT!';
		$subject[] = 'Feb 20 22:27:15 aust sshd[4322]: pam_unix(sshd:auth): authentication failure; logname= uid=0 euid=0 tty=ssh ruser= rhost=122.146.40.72  user=root';
		$subject[] = 'Feb 20 22:27:15 aust sshd[4322]: pam_winbind(sshd:auth): getting password (0x00000388)';
		$subject[] = 'Feb 20 22:27:15 aust sshd[4322]: pam_winbind(sshd:auth): pam_get_item returned a password';
		$subject[] = 'Feb 20 22:27:15 aust sshd[4322]: pam_winbind(sshd:auth): request wbcLogonUser failed: WBC_ERR_AUTH_ERROR, PAM error: PAM_USER_UNKNOWN (10), NTSTATUS: NT_STATUS_NO_SUCH_USER, Error message was: No such user';
		$subject[] = 'Feb 20 22:27:17 aust sshd[4322]: Failed password for root from 122.146.40.72 port 59979 ssh2';		
		//Feb 20 06:25:25
		//$subject = 'Feb' ;
		//$pattern = '/[^:alpha:]+/';
		//preg_match($pattern, $subject, $matches );
		//var_dump($matches);
		//parent::__construct();
		//var_dump(preg_grep('/[a-z]/', $subject));
	
		$line = "reverse mapping checking getaddrinfo for 122-146-40-72.adsl.static.sparqnet.net [122.146.40.72] failed - POSSIBLE BREAK-IN ATTEMPT!'";
		if (preg_match('/[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}/', $line, $res) < 1)
		{
			$this->_szLastError = "Couldn't extract IP from Line : '$line'.";
			$bOk = false;
		}
	
		var_dump($res[0]);
	
	
	
	
		//var_dump($arInfos);
			//var_dump($res);
		
		
		
		//int mktime ([ int $hour = date("H") [, int $minute = date("i") [, int $second = date("s") [, int $month = date("n") [, int $day = date("j") [, int $year = date("Y") [, int $is_dst = -1 ]]]]]]] )
		//nous avons un soucis entre une année et une autre

	}
}

require_once('Includes/Constants.php');


echo 'information' . NEWLINE;

/*
00000001
00000010
00000100
00000111
*/

echo LOG_LEVEL_ERROR . NEWLINE;
echo LOG_LEVEL_WARNING . NEWLINE;
echo LOG_LEVEL_VERBOSE . NEWLINE;
echo LOG_LEVEL_ALL . NEWLINE;

echo "-".NEWLINE;
$loglevel = LOG_LEVEL_ALL ^ LOG_LEVEL_ERROR ;

if ($loglevel & LOG_LEVEL_ERROR)
	echo "error".NEWLINE;
if ($loglevel & LOG_LEVEL_WARNING)
	echo "warning".NEWLINE;
if ($loglevel & LOG_LEVEL_VERBOSE)
	echo "verbose".NEWLINE;


echo "<<<".NEWLINE;

//echo ($levelWarning  LOG_LEVEL_ALL) . NEWLINE;
echo ($levelWarning & LOG_LEVEL_WARNING) .NEWLINE;

echo $levelWarning . NEWLINE;

echo 'fin'.NEWLINE;

//echo substr("abcdef", 1, strlen("abcdef")-2);

/*
$line = 'Feb 20 22:27:15 aust sshd[4322]: pam_winbind(sshd:auth): gett';
if (preg_match('/\[[[:digit:]]+\]/', $line, $match) > 0)
{
	var_dump($match);
}
else
	echo "oups....";
*/
/*
$ar = array('1','2','3');
var_dump($ar);
unset($ar[0]);
var_dump($ar);

date_default_timezone_set('UTC'); 
$sentence ='Feb 20 22:27:17 prout la vie';
$pattern = '/[[:alpha:]]{3} [[:digit:]]{1,2} [[:digit:]]{2}:[[:digit:]]{2}:[[:digit:]]{2}/';
$szDate = preg_match($pattern, $sentence,$match);
var_dump($match);
$tm = strtotime($szDate);
echo $tm;
echo "\r\n";
//int mktime ([ int $hour = date("H") [, int $minute = date("i") [, int $second = date("s") [, int $month = date("n") [, int $day = date("j") [, int $year = date("Y") [, int $is_dst = -1 ]]]]]]] )
$tm = mktime(22,27,17,2,20);
echo $tm;
echo "\r\n";
exit;
*/
/*
$subject = 'Feb 19 17:39:01 aust CRON[7539]: pam_unix(cron:session): session opened for user root by (uid=0)';
$szDate = substr($subject, 0, 15);
$szMonth = substr($szDate, 0,3);
$arTranslate = array(1=>'jan','feb','mar','apr', 'may','jun','jul','aug','sep','oct','nov','dec');
var_dump($translate);
$day = substr($szDate,4,2);
$hours = substr($szDate, 7,2);
$min = substr($szDate,10,2);
$sec = substr($szDate,13,2);
date_default_timezone_set('UTC');
var_dump($month = array_keys($arTranslate, strtolower($szMonth), false));
$tm = mktime($hours, $min, $sec, $month[0], $day);
echo "timestamp = $tm.";
echo "$month.-.$day.-$hours.-.$min.-.$sec.\r\n";
*/
//$obj = new tata();

//----



?>