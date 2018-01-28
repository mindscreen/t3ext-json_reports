<?php

namespace Mindscreen\JsonReports\Output;

/**
 * Output interface
 */
interface OutputInterface
{
    /**
     * @param $reportData
     * @return string
     */
    public function convert($reportData);

}
