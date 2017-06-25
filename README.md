# MySSHBlackList
a script to easily ban IP trying to brutefore your ssh server

I decided to use PhP even if perl would be more suited, because I love PhP

How does it works ?
It simply read the auth.log file with CLogToSurvey
on each ligne of auth.log we do have a date [ CDateInLog ] & associated information about login attempt [ CInfos ]
To have a relevant information we need a date so information if a CDateInLog's child.
Among auth.log we also have hosts.deny containing banned IP [ CBlackListed ]

This script is old but I still use it so I thought I would share it.

How to leverage it ?
sync the project.
put the MySSHBlackList folder in the following folder : /ScriptsMaison/
then as root launch : /ScriptsMaison/launchblacklist.sh
output shall look like this :
```bash
20170625 11:31:53 [ VERBOSE ] : MySSHBlacklist Started.
20170625 11:31:53 [ VERBOSE ] : Memory Available for php :-1
20170625 11:31:53 [ VERBOSE ] : No IP to ban in /var/log/auth.log .
20170625 11:31:53 [ VERBOSE ] : MySSHBlacklist Ended.
```

If IP have been ban they will be listed in place of No IP to ban in /var/log/auth.log


