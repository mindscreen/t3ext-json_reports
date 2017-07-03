<?php
namespace Mindscreen\JsonReports\Command;


use TYPO3\CMS\Extbase\Mvc\Controller\CommandController;
use TYPO3\CMS\Reports\Status;
use TYPO3\CMS\Reports\StatusProviderInterface;

/**
 * @package Mindscreen\UserExtranetRefund\Command
 */
class ReportsCommandController extends CommandController
{

    /**
     * List all report results as JSON
     */
    public function listCommand()
    {
        $result = [];
        /** @var StatusProviderInterface $provider */
        foreach ($GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['reports']['tx_reports']['status']['providers'] as $category => $providers) {
            $result[$category] = [];
            foreach ($providers as $providerClass) {
                $provider = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance($providerClass);
                if ($provider instanceof StatusProviderInterface) {
                    $statusArray = $provider->getStatus();
                    /** @var Status $statusItem */
                    foreach ($statusArray as $statusItem) {
                        $result[$category][] = [
                            // Fallback title for reports that use unavailable translation functionality
                            'title' => $statusItem->getTitle() ?: 'Please check reports module in TYPO3 backend',
                            'value' => $statusItem->getValue(),
                            'message' => $statusItem->getMessage(),
                            'severity' => $statusItem->getSeverity(),
                        ];
                    }
                }
            }
        }
        echo json_encode($result);
    }

}