<?Php

require_once('Includes/Constants.php');

class CLog
{
	//Une seule instance pour toute la durée du script suffira..
	private static $_hInstance;
	//Nom du fichier de Log;
	private $_fNameLog;
	
	private function __construct()
	{
		$this->_fNameLog = CFG_LOG_FILE;
	}
	
	public static function GetInstance()
	{
		if (! isset(self::$_hInstance) )
		{
			self::$_hInstance = new CLog();
		}
		
		return self::$_hInstance;
	}
	
	//__clone magic copy constructor : DISABLED
	private function __clone()
	{
		return NULL;
	}
	
	private function _WriteInLog($fname, $toWrite)
	{
		$bOk = false;
		$hFile = fopen($this->_fNameLog, 'a');
		
		if (($hFile === FALSE) || ($hFile === NULL))
		{
			$this->slastError = "Impossible d'ouvrir le fichier ".$this->_fNameLog." .";
			return false;
		}
		else 
		{
			$bOk = fwrite($hFile, $toWrite);
			if ($bOk === false)
			{
				$this->slastError = "Impossible d'ecrire dans le fichier ".$this->_fNameLog." .";
			}
			fclose($hFile);
		}
		
		return ($bOk);
	}
	
	private function _GetSZLogLevel(&$nLogLevel)
	{
		$szLogLevel = "unknow";
		
		if ($nLogLevel & LOG_LEVEL_ERROR)
			$szLogLevel = "[ ERROR ]";
		if ($nLogLevel & LOG_LEVEL_WARNING)
			$szLogLevel = "[ WARNING ]";
		if ($nLogLevel & LOG_LEVEL_VERBOSE)
			$szLogLevel = "[ VERBOSE ]";
			
		return ($szLogLevel);
	}
	
	public function LogInfo($information, $nLogLevelOfThisMessage = LOG_LEVEL_ERROR)
	{
		$bOk = true;
		$bWrite = false;
		$szKindOfLog = "";

		//On verifie que l'on peux ecrire fonction du niveau de log authorisé par LOG_LEVEL
		if ($nLogLevelOfThisMessage & AUTHORIZED_LOG_LEVEL)
			$bWrite = true;
				
		//si oui on log avec le niveau de log approprié.
		if ($bWrite == true)
		{
			$szLineToLog = "";

			$szPrefix = date("Ymd H:i:s");
			$szLogLevel = ' ' . $this->_GetSZLogLevel($nLogLevelOfThisMessage) ;
			$szLineToLog = $szPrefix . $szLogLevel . ' : '.$information . NEWLINE;
			echo $szLineToLog;
			$bOk = $this->_WriteInLog($this->_fNameLog, $szLineToLog);
		}
		
		return $bOk;
	}
	
	/*
	public LogError($error, $errnum)
	{
		$szLineToLog = "";

		$szPrefix = date("Ymd H:i:s");
		$szLineToLog = $prefix . ' : '.$information . NEWLINE;
		
		$bOk = $this->_WriteInLog($szLineToLog);
		
	}*/
	
	function __destruct()
	{
		
	}
	
}


?>