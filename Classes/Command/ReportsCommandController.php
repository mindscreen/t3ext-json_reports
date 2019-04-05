<?php
namespace Mindscreen\JsonReports\Command;

use Mindscreen\JsonReports\Output\OutputInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;
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
     * @var array
     */
    protected $groupConfiguration = [];

    /**
     * Output report results
     *
     * @param string $format The desired output format (defaults to json)
     * @param string $group The report group to display
     * @throws Exception
     * @throws \TYPO3\CMS\Extbase\Mvc\Exception\StopActionException
     */
    public function listCommand($format = 'json', $group = 'default')
    {
        $this->getLanguageService()->includeLLFile('EXT:reports/Resources/Private/Language/locallang_reports.xlf');

        if (!isset($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['json_reports']['groups'][$group])) {
            throw new Exception('The report group  "' . $group . '" has not been configured.', 1554465727);
        }
        $this->groupConfiguration = $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['json_reports']['groups'][$group];

        $result = [];
        /** @var StatusProviderInterface $provider */
        foreach ($GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['reports']['tx_reports']['status']['providers'] as $category => $providers) {
            $result[$category] = [];
            foreach ($providers as $providerClass) {
                $provider = GeneralUtility::makeInstance($providerClass);
                if ($provider instanceof StatusProviderInterface) {
                    $statusArray = $provider->getStatus();
                    /** @var Status $statusItem */
                    foreach ($statusArray as $statusItem) {
                        if (!$this->isExcludedFromGroup($category, $statusItem->getTitle())) {
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

    /**
     * @param string $category
     * @param string $title
     * @return boolean
     */
    protected function isExcludedFromGroup($category, $title)
    {
        if (isset($this->groupConfiguration['exclude'][$category])
            && is_array($this->groupConfiguration['exclude'][$category])) {
            if (in_array('*', $this->groupConfiguration['exclude'][$category])
                || in_array($title, $this->groupConfiguration['exclude'][$category])) {
                return true;
            }
        }
        return false;
    }
}
