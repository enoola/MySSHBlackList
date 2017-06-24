<?Php

//apply filters to the log

require_once('Includes/Constants.php');

class CFilter
{
	private $_arRules;
	private $_oRefReadLog;
	private $_szLastError;
	
	function __construct($oRefReadLog, $arRules = array())
	{
		$this->_arRules = array();
		$this->_oRefReadLog = $oRefReadLog;
		$this->_szLastError = "";
	}
	
	function GetMatching()
	{
		$arObj = $this->_oRefReadLog->GetObjectizedFile();

		$arMatching = array();
		if (count($arObj) > 0)
		{
			foreach ($arObj as $k => $o)
			{	
			 	foreach ($this->_arRules as $k => $r)
					if (strpos($o->GetInfos(), $r) !== FALSE )
						$arMatching[] = $o;
			}
		}
		
		return ($arMatching);
	}
	
	function GetLastError()
	{
		return ($this->_szLastError);
	}
	
	function AddRule($szRule)
	{
		$this->_arRules[] = $szRule;
	}
	
}


?>