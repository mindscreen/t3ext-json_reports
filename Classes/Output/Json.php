<?php

namespace Mindscreen\JsonReports\Output;

/**
 * JSON output for reports
 */
class Json implements OutputInterface
{

    /**
     * @param $reportData
     * @return string
     */
    public function convert($reportData)
    {
        return json_encode($reportData);
    }

}
