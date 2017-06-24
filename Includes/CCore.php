<?Php

require_once("Includes/Constants.php");

class CCore
{	
	private $_arObjectizedFile;
	private $_arIPToBan;
	//Interval we look at
	private $_intervalToBanInSec;
	//number of time we see the IP in the intervalToBanInSec
	private $_numberOfTimeSeen;
	private $_bannedIP;
	private $_arOMatching;
	
	function __construct(&$arOMatching, $intervalToBanInSec, $numberOfTimeSeen)
	{
		$this->_arOMatching = $arOMatching;
		$this->_arIPToBan = array();
		$this->_intervalToBanInSec = $intervalToBanInSec;
		$this->_numberOfTimeSeen = $numberOfTimeSeen;
		$this->_bannedIP = array();
	}
	
	function GetIpToBan()
	{
		return ($this->_bannedIP);
	}
	
	/*
	 * MUST BE TESTED
	 *
	*/
	private function _DoesIpMustBeBan($szIP)
	{
		$bMustBeBanned = false;
		$arTimestamp = $this->_arReorderedIP[$szIP];
		$nViewed = 0;
		$nCount = 1;

		$nTimestampStart = $arTimestamp[0];
		
		/*
		 * We go through each key because we do not want to Apply
		 * on a longer interval that necessary
		*/ 
		next($arTimestamp);
		while (($bMustBeBanned == false) && (count($arTimestamp) >= $this->_numberOfTimeSeen)) {
			foreach ($arTimestamp as $k => $timestamp)
			{
				$nCount++;
				$nDiffSec = $timestamp - $arTimestamp[0];
				if ($nDiffSec <= $this->_intervalToBanInSec)
					if ($nCount >= $this->_numberOfTimeSeen)
						{
							$bMustBeBanned = true;
							break;
						}
			}
			/*
			 * If we didn't got a match we shift the first element,
			 * which is the stat point of our interval
			*/
			if (!$bMustBeBanned)
				array_shift($arTimestamp);
		}
		return ($bMustBeBanned);
	}
	
	private function _CalcIPsToBan()
	{
		foreach ($this->_arReorderedIP as $ip => $ar)
		{
			if ($this->_DoesIpMustBeBan($ip))
				$this->_bannedIP[] = $ip;
		}
		return ;
	}
	
	function Run()
	{
		$bOk = true;
		
		$this->_RemoveMultiplePidFromArOMatching();
		
		$arReorderedIPtm = $this->_ReorderResults();

		$this->_CalcIPsToBan();
		return $bOk;
	}
	
	//supprime PID multiple
	private function _RemoveMultiplePidFromArOMatching()
	{
		$arFlushOMatching = array();
		$nCpt = count($this->_arOMatching);
		
		if ($nCpt > 0)
			$curOInfos = $this->_arOMatching[0];
		for ($i = 0; $i < $nCpt; ++$i)
		{
			if ($curOInfos->GetPid() == $this->_arOMatching[$i]->GetPid())
				unset($this->_arOMatching[$i]);
			else 
			{
				$curOInfos = $this->_arOMatching[$i];
				$arFlushOMatching[] = $curOInfos;
				--$i;
			}
		}
		unset($this->_arOMatching);
		$this->_arOMatching = $arFlushOMatching;
		unset($arFlushOMatching);

		return ;
	}
	
	//recoit un tableau de la forme array('tm', 'ip')
	private function _ReorderResults()
	{
		$arCopyOMatching = $this->_arOMatching;
		
		$getOneIp = false;
		$this->_arReorderedIP = array();

		while (count ($arCopyOMatching) > 0)
		{
			$keyToDel = array();
			foreach ($arCopyOMatching as $i => $val)
			{
				$entry = $arCopyOMatching[$i];
			
				if (($entry->GetIp() != 0) && ($getOneIp == false))
				{
					$curIP = $entry->GetIp();
					$this->_arReorderedIP[$entry->GetIp()] = array();
					$getOneIp = true;
				}
				
				if (($getOneIp == true ) && ($entry->GetIp() != false) && (strcmp($entry->GetIp(), $curIP)==0))
				{
					$this->_arReorderedIP[$entry->GetIp()][] = $entry->GetTimestamp();
				}
				if (($entry->GetIp() == false) || (strcmp($entry->GetIp(), $curIP)==0))
				{
					$keyToDel[] = $i;
				}
				
			}
			foreach ($keyToDel as $k => $kToDel)
				unset($arCopyOMatching[$kToDel]);
			$getOneIp = false;
		}
		return ($this->_arReorderedIP);
	}
	
	
	
}




?>