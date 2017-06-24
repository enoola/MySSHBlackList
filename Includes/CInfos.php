<?Php

//require_once('Includes/CDateInLog.php');
/*
 * CReadAuthLog
 *
*/
//Typically information is a ligne without a date
class CInfos 
{
	//informationo que nous ne transformons pas en objet
	private $_szInfo;
	//objet date "a nous"
	private $_timestamp;
	private $_szLastError;
	private $_pid;
	private $_ip;
	
	function __construct()
	{
		$this->_szLastError = '';
		$this->_timestamp = 0;
		$this->_szInfo = '';
	}
	
	private function _Auth_ExtractInformations(&$line)
	{
		
		$bOk = false;
		
		if ($this->_SetInfosFromLine($line))
			if ($this->_SetPidFromLine($line))
				if ($this->_SetTimestampFromLine($line))
					if ($this->_SetIPFromLine($line))
						$bOk = true;

		return $bOk;
	}

	function _SetInfosFromLine(&$line)
	{
		$bOk = true;
		
		if (preg_match('/:[ ]{1}[[:ascii:]]+/', $line, $match) < 1)
		{
			$this->_szLastError = "Line '$line' couldn't match informations pattern.";
			$bOk = false;
		}
		else
			$this->_szInfo = $match[0];
		return ($bOk);
	}
	
	function _SetPidFromLine(&$line)
	{
		$bOk = false;
		
		if (preg_match('/\[[[:digit:]]{2,8}\]/', $line, $match) > 0)
		{
			$this->_pid = substr($match[0],1, strlen($match[0])-2);
			$bOk = true;
		}
		else
			$this->_szLastError = "Couldn't match date on line '$line'";
		
		return ($bOk);
	}
	
	function _SetTimestampFromLine(&$line)
	{
		$bOk = true;
		
		$szPatternDate = '/[[:alpha:]]{3} [[:digit:]]{1,2} [[:digit:]]{2}:[[:digit:]]{2}:[[:digit:]]{2}/';
		if (preg_match($szPatternDate, $line, $match) > 0)
			$this->_timestamp = strtotime( $match[0] );
		else
		{
			$this->_szLastError = "Couldn't match date on line '$line'";
			$bOk = false;
		}

		return ($bOk);
	}

	function _SetIPFromLine(&$line)
	{
		$bOk = true;
		
		if (preg_match('/[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}/', $line, $arRes) < 1)
		{
			$bOk = false;
			$this->_szLastError = "Couldn't extract IP from Line : '$line'.";
		}
		if (is_array($arRes) && (count($arRes) > 0))
			$this->_ip = $arRes[0];
		
		return $bOk;
	}

	function Load($line)
	{
		$bOk = false;

		$bOk = $this->_Auth_ExtractInformations($line);
		
		return ($bOk);
	}
	
	function GetInfos()
	{
		return ($this->_szInfo);
	}
	
	function GetIP()
	{
		return ($this->_ip);
	}
	
	function GetPid()
	{
		return ($this->_pid);
	}
	
	function GetTimestamp()
	{
		return ($this->_timestamp);
	}

	function GetLastError()
	{
		return ($this->_szLastError);
	}

	function __destruct()
	{
		
	}
	
}



?>