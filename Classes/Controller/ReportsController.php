<?php

namespace Mindscreen\JsonReports\Controller;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Core\Http\Stream;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Class ReportsController
 */
class ReportsController
{

    /**
     * Main method, used as eID script
     *
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @return NULL|ResponseInterface
     */
    public function indexAction(ServerRequestInterface $request, ResponseInterface $response)
    {
        $responseBody = fopen('php://memory', 'r+');
        $statusCode = 200;
        $statusMessage = 'All reports OK';

        // Check if current IP address is in range
        if (!GeneralUtility::cmpIP(GeneralUtility::getIndpEnv('REMOTE_ADDR'),
            $GLOBALS['EXTCONF']['json_reports']['allowedIpAddresses'])
        ) {
            $statusCode = 403;
            $statusMessage = 'Access denied';
            fwrite($responseBody, json_encode(['error' => 'Access denied']));
        } else {
            // execute CLI script
            $reportsJson = exec('TYPO3_CONTEXT=' . \TYPO3\CMS\Core\Utility\GeneralUtility::getApplicationContext()->__toString() . ' ' . PATH_site . 'typo3/cli_dispatch.phpsh extbase reports:list');

            $reports = json_decode($reportsJson, true);
            if (!is_array($reports)) {
                fwrite($responseBody, json_encode(['error' => $reportsJson]));
                $statusCode = 500;
                $statusMessage = 'Reports could not be generated';
            } else {
                fwrite($responseBody, $reportsJson);
                foreach ($reports as $reportCategory) {
                    foreach ($reportCategory as $status) {
                        if ($status['severity'] > 0) {
                            $statusCode = 500;
                            $statusMessage = 'Reports contain warnings or errors';
                            break 2;
                        }
                    }
                }
            }
        }

        rewind($responseBody);
        return $response
            ->withHeader('Content-type', 'application/json')
            ->withBody(new Stream($responseBody))
            ->withStatus($statusCode, $statusMessage);

    }
}
