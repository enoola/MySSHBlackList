<?Php

require_once('Includes/Constants.php');
//require_once('Includes/CDateInLog.php');
require_once('Includes/CInfos.php');
require_once('Includes/CLog.php');

class CReadLog
{
	private $_arContentFile;
	private $_sz_szfname;
	private $_arOContentFile;
	private $_szLastError;
	
	function __construct()
	{
		$this->_arContentFile = "";
		$this->_szLastError = "";
		$this->_arOContentFile = array();
		$this->_szfname = CFG_AUTH_FILE;
	}

	private function _LoadFile()
	{
		$bOk = true;
		
		if (!file_exists($this->_szfname))
		{
			$this->_szLastError = "Le fichier ".$this->_szfname." n'existe pas.";
			$bOk = false;
		}
		else if (!is_writable($this->_szfname))
		{	
			$this->_szLastError = "Le fichier ".$this->_szfname." est en lecture seule.";
			$bOk = false;
		}
		else
		{
			$this->_arContentFile = file($this->_szfname, FILE_SKIP_EMPTY_LINES | FILE_IGNORE_NEW_LINES);
			if ($this->_arContentFile == FALSE)
			{
				$this->_szLastError = "Le fichier ".$this->_szfname." est illisible.";
				$bOk = false;
			}
			else if (count($this->_arContentFile) == 0)
			{
				$this->_szLastError = "Le fichier ".$this->_szfname." ne doit pas etre vide !";
				$bOk = false;
			}
		}
		return ($bOk);	
	}

	function GetLastError()
	{
		return ($this->_szLastError);
	}

	function Load()
	{
		$bOk = true;

		$bOk = $this->_LoadFile();

		$i = 0;
		if ($bOk == true)
		{
			$cpt = count($this->_arContentFile);
			while ( $i < $cpt)
			{
				$l = &$this->_arContentFile[$i];

				$oCInfos = new CInfos();
				$bOkb = $oCInfos->Load($l);
				if($bOkb)
					$this->_arOContentFile[] = $oCInfos;
				//else
				//	CLog::GetInstance()->LogInfo($oCInfos->GetLastError(), LOG_LEVEL_VERBOSE);
				unset($this->_arContentFile[$i]);
				$i++;
			}
			if (count($this->_arOContentFile) > 0 )
			{
				$this->_szLastError = "No Match line in file";
			}
			//if (count($this->_arOContentFile) > 0 );
		}

		return $bOk ;
	}

	function GetObjectizedFile()
	{
		return ($this->_arOContentFile);
	}
	
	
	function __destruct()
	{
		
	}
	
	
	
}



?>