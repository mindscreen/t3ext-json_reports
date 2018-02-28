<?php

namespace Mindscreen\JsonReports\Output;

/**
 * JSON output for reports
 */
abstract class AbstractOutput implements OutputInterface
{

    /**
     * @var array
     */
    protected $reportData;

    /**
     * @param $reportData
     */
    public function __construct($reportData)
    {
        $this->reportData = $reportData;
    }

}
