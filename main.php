#!/usr/bin/php
<?Php
/*
 * main caller
*/

date_default_timezone_set('Europe/Berlin'); 
error_reporting(-1);

require_once('Includes/CReadLog.php');
require_once('Includes/Constants.php');
require_once('Includes/CFilter.php');
require_once('Includes/CCore.php');
require_once('Includes/CLog.php');
require_once('Includes/CHostsFiles.php');

//ini_set("memory_limit", '128M');
//echo ini_get("memory_limit") . NEWLINE;
//exit;


CLog::GetInstance()->LogInfo("MySSHBlacklist Started.", LOG_LEVEL_VERBOSE);
CLog::GetInstance()->LogInfo("Memory Available for php :".ini_get('memory_limit'), LOG_LEVEL_VERBOSE);

$oHostsFiles = new CHostsFiles(CFG_HOSTSDENY_FILE, CFG_HOSTSALLOW_FILE);

if (!($oHostsFiles->LoadHostsAllow()  && $oHostsFiles->LoadHostsDeny())) {
	CLog::GetInstance()->LogInfo($oHostsFiles->GetLastError(), LOG_LEVEL_ERROR);
	exit;
}

$oReadLog = new CReadLog();
if (!$oReadLog->Load()) {
	CLog::GetInstance()->LogInfo($oReadLog->GetLastError(), LOG_LEVEL_ERROR);
	exit;
}
$arObj = $oReadLog->GetObjectizedFile();
//if (count($arObj)==0)
//{
//	CLog::GetInstance()->LogInfo("No IP to ban (even before filter) in " . CFG_AUTH_FILE . ' .', LOG_LEVEL_VERBOSE);
//}

//ajout de regle de filtrage
$oFilter = new CFilter($oReadLog);
$oFilter->AddRule("Failed password for");
$oFilter->AddRule("POSSIBLE BREAK-IN ATTEMPT!");
$oFilter->AddRule("PAM_USER_UNKNOWN");
$oFilter->AddRule("Did not receive identification string");

//Matching objects
$arMatching = $oFilter->GetMatching();
$oAnalyse = new CCore($arMatching, INTERVAL_TO_LOOKUP, MAX_NUMBER_ATTACK);
$arReorder = $oAnalyse->Run();
$arIpToBan = $oAnalyse->GetIpToBan();

//Ip To ban
if (count($arIpToBan) == 0) {
	CLog::GetInstance()->LogInfo("No IP to ban in " . CFG_AUTH_FILE . ' .', LOG_LEVEL_VERBOSE);
}

//Now we can verify IPs we matched aren't in the white list
//If so we removed them.
$oHostsFiles->SetIPToBanRemovingUselessOnes($arIpToBan);
if (!$oHostsFiles->WriteHostsDeny($arIpToBan))
	CLog::GetInstance()->LogInfo($oHostsFiles->GetLastError(), LOG_LEVEL_ERROR);

CLog::GetInstance()->LogInfo("MySSHBlacklist Ended.", LOG_LEVEL_VERBOSE);
exit;

?>