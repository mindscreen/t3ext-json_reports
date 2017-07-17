<?php
namespace Mindscreen\JsonReports\Command;


use TYPO3\CMS\Extbase\Mvc\Controller\CommandController;
use TYPO3\CMS\Lang\LanguageService;
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
        $this->getLanguageService()->includeLLFile('EXT:reports/Resources/Private/Language/locallang_reports.xlf');

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
                            'title' => $statusItem->getTitle(),
                            'value' => $statusItem->getValue(),
                            'message' => $statusItem->getMessage(),
                            'severity' => $statusItem->getSeverity(),
                        ];
                    }
                }
            }
        }
        $this->output->output(json_encode($result));
    }

    /**
     * Returns the Language Service
     * @return LanguageService
     */
    protected function getLanguageService()
    {
        return $GLOBALS['LANG'];
    }

}