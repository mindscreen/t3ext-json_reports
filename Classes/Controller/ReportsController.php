<?php
namespace Mindscreen\JsonReports\Controller;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Class ReportsController
 */
class ReportsController {

    /**
     * Main method, used as eID script
     *
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @return NULL|ResponseInterface
     */
    public function indexAction(ServerRequestInterface $request, ResponseInterface $response)
    {
        // Check if current IP address is in range
        if (!GeneralUtility::cmpIP(GeneralUtility::getIndpEnv('REMOTE_ADDR'), $GLOBALS['EXTCONF']['json_reports']['allowedIpAddresses'])) {
            echo 'Access denied for IP ' . GeneralUtility::getIndpEnv('REMOTE_ADDR');
            return $response->withStatus('403');
        }

        // execute CLI script
        $reportsJson = exec('TYPO3_CONTEXT=' . getenv('TYPO3_CONTEXT') . ' '  . PATH_site . 'typo3/cli_dispatch.phpsh extbase reports:list');
        echo $reportsJson;

        $reports = json_decode($reportsJson, true);

        if (!is_array($reports)) {
            return $response->withStatus('500', 'Reports could not be generated.');
        }

        foreach ($reports as $reportCategory)  {
            foreach ($reportCategory as $status) {
                if ($status['severity'] > 0) {
                    return $response->withStatus('500', 'Reports contain warnings or errors.');
                }
            }
        }
    }
}