<?Php

require_once("Includes/Constants.php");

class CHostsFiles
{

	private $_fnameHostsDeny;
	private $_fnameHostsAllow;
	private $_ipInFileHostsDeny;
	private $_ipInFileHostsAllow;
	private $_szLastError;
	private $_arIpToBan;

	function __construct($fnameHostsDeny, $fnameHostsAllow)
	{
		$this->_fnameHostsDeny = $fnameHostsDeny;
		$this->_fnameHostsAllow = $fnameHostsAllow;
		$this->_arIpToBan = array();
		$this->_ipInFileHostsDeny = array();
		$this->_ipInFileHostsAllow = array();
		$this->_szLastError = "";
	}

	private function _GetContentHostsFile($szFname, &$cttFile)
	{
		$bOk = true;
		$arFile = array();

		if (!file_exists($szFname))
		{
			$this->_szLastError = "Le fichier ".$szFname." n'existe pas.";
			$bOk = false;
		}
		else if (!is_writable($szFname))
		{
			$this->_szLastError = "Le fichier ".$szFname." est en lecture seule.";
			$bOk = false;
		}
		else
		{
			$arFile = file($szFname, FILE_SKIP_EMPTY_LINES | FILE_IGNORE_NEW_LINES);
			if ($arFile == FALSE)
			{
				$this->_szLastError = "Le fichier ".$szFname." est illisible.";
				$bOk = false;
			}
			else if (count($arFile) == 0)
			{
				$this->_szLastError = "Le fichier ".$szFname." ne doit pas etre vide !";
				$bOk = false;
			}
		}
		if ($bOk == true)
		{
			$cpAr = $arFile;
			$cttFile = array();

			foreach($cpAr as $k => $line)
			{
				if (strncmp('#', $line, 1) == 0)
				{
					unset($cpAr[$k]);
				}
				else
				{
					$cttFile[] = $line;
				}
			}
		}

		return ($bOk);
	}

	//peut etre amelioré en prenant en compte le type de regle
	private function _getIpsFromArray($arLine)
	{
		$arIPs = array();

		foreach ($arLine as $k => $line)
		{

			$arRes = array();
			if (preg_match('/[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}/', $line, $arRes) < 1)
			{
				$bOk = false;
				$this->_szLastError = "Couldn't extract IP from Line : '$line'.";
			}
			if (is_array($arRes) && (count($arRes) > 0))
				$arIPs[] = $arRes[0];
		}

		return ($arIPs);
	}

	function LoadHostsAllow()
	{
		$arCttF = array();
		$this->_ipInFileHostsAllow = array();

		$bOk = $this->_GetContentHostsFile($this->_fnameHostsAllow, $arCttF);
		if ($bOk == true)
			$this->_ipInFileHostsAllow = $this->_getIpsFromArray($arCttF);

		return ($bOk);
	}

	function LoadHostsDeny()
	{
		$arCttF = array();
		$this->_ipInFileHostsDeny = array();

		$bOk = $this->_GetContentHostsFile($this->_fnameHostsDeny, $arCttF);
		if ($bOk == true)
			$this->_ipInFileHostsDeny = $this->_getIpsFromArray($arCttF);

		return ($bOk);
	}

	function GetIpInHostsAllow()
	{
		return ($this->_ipInFileHostsAllow);
	}

	function GetIpInHostsDeny()
	{
		return ($this->_ipInFileHostsDeny);
	}

	function SetIPToBanRemovingUselessOnes($arIpToBan)
	{
		if (count($this->_ipInFileHostsAllow) > 0)
			foreach ($this->_ipInFileHostsAllow as $k => $oneWhiteIP)
			{
				foreach ($arIpToBan as $n => $oneIPToBan)
					if (strcmp($oneWhiteIP, $oneIPToBan)==0) {
						//CLog::GetInstance()->LogInfo("Won't ban IP : ".$oneWhiteIP.' because presents in ' . CFG_HOSTSALLOW_FILE . '.', LOG_LEVEL_ERROR);
						unset($arIpToBan[$n]);
					}
			}
		if (count($this->_ipInFileHostsDeny) > 0)
			foreach ($this->_ipInFileHostsDeny as $k => $oneBlackIP)
			{
				foreach ($arIpToBan as $n => $oneIPToBan)
					if (strcmp($oneBlackIP, $oneIPToBan)==0) {
						//CLog::GetInstance()->LogInfo("Won't ban IP : ".$oneBlackIP.' because presents in ' . CFG_HOSTSDENY_FILE . '.', LOG_LEVEL_ERROR);
						unset($arIpToBan[$n]);
				}
			}
		$this->_arIpToBan = $arIpToBan;

		return ($this->_arIpToBan);
	}

	function WriteHostsDeny()
	{
		$bOk = false;
		if (count($this->_arIpToBan) == 0)
			return true;
		$hFile = fopen($this->_fnameHostsDeny, 'a');

		if (($hFile === FALSE) || ($hFile === NULL))
		{
			$this->slastError = "Impossible d'ouvrir le fichier ".$this->_fNameLog." .";
			$bOk = false;
		}
		else
		{
			foreach ($this->_arIpToBan as $k => $ip)
			{
				$toWrite = 'ALL: '. $ip . NEWLINE;
				$bOk = fwrite($hFile, $toWrite);
				if ($bOk === false)
				{
					$this->slastError = "Impossible d'ecrire dans le fichier ".$this->_fnameHostsDeny." .";
				}
				else
					CLog::GetInstance()->LogInfo("banned IP : ".$ip . '.', LOG_LEVEL_ERROR);
				unset($toWrite);
			}
			fclose($hFile);
		}

		return ($bOk);
	}

	function GetLastError()
	{
		return ($this->_szLastError);
	}

}



?>
