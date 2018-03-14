<?php

namespace Mindscreen\JsonReports\Output;

use TYPO3\CMS\Reports\Status;

/**
 * JSON output for reports
 */
class Nagios extends AbstractOutput
{

    /**
     * @var array
     */
    protected $count = [
        '-2' => 0,
        '-1' => 0,
        '0' => 0,
        '1' => 0,
        '2' => 0,
    ];

    /**
     * @var array
     */
    protected $messages = [];

    /**
     * @param $reportData
     */
    public function __construct($reportData)
    {
        parent::__construct($reportData);

        foreach ($reportData as $reportCategory) {
            foreach ($reportCategory as $status) {
                $this->count[(string)$status['severity']]++;
                if ($status['severity'] === Status::ERROR) {
                    $this->messages[] = $status['title'] . ': ' . $status['value'] . ';';
                }
            }
        }
    }

    /**
     * @return string
     */
    public function getText()
    {
        if ($this->count['1'] === 0 && $this->count['2'] === 0) {
            $textOutput = 'No warnings or errors in TYPO3 reports';
        } elseif ($this->count['2'] === 0) {
            $textOutput = sprintf('%s warning(s) in TYPO3 reports', $this->count['1']);
        } else {
            $textOutput = sprintf('%s error(s) in TYPO3 reports', $this->count['2']);
        }

        $performanceData = sprintf('NOTICE=%s INFO=%s OK=%s WARNING=%s ERROR=%s', $this->count['-2'], $this->count['-1'], $this->count['0'], $this->count['1'], $this->count['2']);

        return $textOutput . ' | ' . $performanceData . PHP_EOL . implode(PHP_EOL, $this->messages);
    }

    /**
     * @return int
     */
    public function getExitCode()
    {
        if ($this->count['1'] === 0 && $this->count['2'] === 0) {
            $returnCode = 0;
        } elseif ($this->count['2'] === 0) {
            $returnCode = 1;
        } else {
            $returnCode = 2;
        }
        return $returnCode;
    }

}
