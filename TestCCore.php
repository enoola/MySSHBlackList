<?Php



error_reporting(-1);

require_once('Includes/CReadLog.php');
require_once('Includes/Constants.php');
require_once('Includes/CFilter.php');
require_once('Includes/CCore.php');

$oReadLog = new CReadLog();
if (!$oReadLog->Load())
	echo $this->GetLastError() . NEWLINE;

//$arObj = $oReadLog->GetObjectizedFile();


$oFilter = new CFilter($oReadLog);
$oFilter->AddRule("Failed password for invalid user");
$oFilter->AddRule("POSSIBLE BREAK-IN ATTEMPT!");
$oFilter->AddRule("PAM_USER_UNKNOWN");

//$oFiler->AddRule();
$arObj = $oReadLog->GetObjectizedFile();
//var_dump($oFilter->GetMatching());

$oAnalyse = new CCore($oFilter->GetMatching(), 60, 5);
//$arReorder = $oAnalyse->ReorderResults();
$arReorder = $oAnalyse->Run();
var_dump($arReorder);
echo '------' . NEWLINE;
$ipToBan = $oAnalyse->GetIpToBan();

echo '------' . NEWLINE;
var_dump($ipToBan);

foreach ($ipToBan as $k => $ip)
{
	echo 'ALL: '.$ip.NEWLINE;
}





?>