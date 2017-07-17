[![License: GPL v3](https://img.shields.io/badge/License-GPL%20v3-blue.svg)](https://www.gnu.org/licenses/gpl-3.0)
![Packagist][packagist]

[packagist]: https://img.shields.io/packagist/v/mindscreen/json-reports.svg

# JSON Reports for TYPO3

This TYPO3 extension adds a CLI command and an HTTP endpoint that outputs the reports that you can find in the reports 
module as JSON. The JSON output can be used in monitoring or alerting systems. The HTTP endpoint can be protected via 
IP restriction.
 
## CLI command

You can access the reports JSON via CLI with the following command:

```typo3/cli_dispatch.phpsh extbase reports:list```


## HTTP endpoint

In order to use the HTTP endpoint you have to configure the allowed IP addresses via the following setting:

```$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['json_reports']['allowedIpAddresses'] = '';```

Clients that match the allowed IP address range can access the reports JSON via the following URL:

```https://my.domain/?eID=json_reports```

If the reports contain warnings or errors (or there are errors during report generation), a status 500 is returned.
