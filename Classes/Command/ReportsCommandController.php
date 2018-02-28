<?php
namespace Mindscreen\JsonReports\Command;

use Mindscreen\JsonReports\Output\OutputInterface;
use TYPO3\CMS\Extbase\Configuration\Exception;
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
     * Output report results
     *
     * @param string $format
     * @throws Exception
     * @throws \TYPO3\CMS\Extbase\Mvc\Exception\StopActionException
     */
    public function listCommand($format = 'json')
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

        if (!isset($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['json_reports']['output'][$format])) {
            throw new Exception('The output class for format "' . $format . '" has not been configured.', 1517161193);
        }
        $output = $this->objectManager->get($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['json_reports']['output'][$format], $result);
        if (!$output instanceof OutputInterface) {
            throw new Exception('The output class "' . get_class($output) . '" does not implement OutputInterface.', 1517161194);
        }

        $this->output->output($output->getText());
        $this->sendAndExit($output->getExitCode());
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