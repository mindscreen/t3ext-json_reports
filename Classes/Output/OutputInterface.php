<?php

namespace Mindscreen\JsonReports\Output;

/**
 * Output interface
 */
interface OutputInterface
{

    /**
     * OutputInterface constructor.
     * @param $reportData
     */
    public function __construct($reportData);

    /**
     * @return string
     */
    public function getText();

    /**
     * @return int
     */
    public function getExitCode();
}
