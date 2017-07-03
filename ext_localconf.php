<?php

if (!defined('TYPO3_MODE')) {
    die('Access denied.');
}

// Access to the reports script via HTTP is only granted to the IP addresses or address range defined here
$GLOBALS['EXTCONF']['json_reports']['allowedIpAddresses'] = '';

// Register eID Script
$GLOBALS['TYPO3_CONF_VARS']['FE']['eID_include']['json_reports'] = \Mindscreen\JsonReports\Controller\ReportsController::class . '::indexAction';

if (TYPO3_MODE === 'BE') {
    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['extbase']['commandControllers'][] = \Mindscreen\JsonReports\Command\ReportsCommandController::class;
}
