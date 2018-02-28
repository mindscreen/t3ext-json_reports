<?php

namespace Mindscreen\JsonReports\Output;

/**
 * JSON output for reports
 */
class Json extends AbstractOutput
{

    /**
     * @return string
     */
    public function getText()
    {
        return json_encode($this->reportData);
    }

    /**
     * @return int
     */
    public function getExitCode()
    {
        return 0;
    }


}
