<?php

if (!defined('TYPO3_MODE')) {
    die('Access denied.');
}

// Access to the reports script via HTTP is only granted to the allowed IP addresses or address range
if (!isset($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['json_reports']['allowedIpAddresses'])) {
    $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['json_reports']['allowedIpAddresses'] = '';
}

if (!isset($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['json_reports']['output'])) {
    $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['json_reports']['output'] = [];
}
$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['json_reports']['output']['json'] = \Mindscreen\JsonReports\Output\Json::class;


// Register eID Script
$GLOBALS['TYPO3_CONF_VARS']['FE']['eID_include']['json_reports'] = \Mindscreen\JsonReports\Controller\ReportsController::class . '::indexAction';

if (TYPO3_MODE === 'BE') {
    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['extbase']['commandControllers'][] = \Mindscreen\JsonReports\Command\ReportsCommandController::class;
}
