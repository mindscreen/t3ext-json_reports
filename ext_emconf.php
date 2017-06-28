<?php

/***************************************************************
 * Extension Manager/Repository config file for ext "denyfegroup".
 ***************************************************************/

$EM_CONF[$_EXTKEY] = array (
    'title' => 'JSON Reports',
    'description' => 'Outputs reports as JSON for processing in monitoring or alerting systems.',
    'category' => 'fe',
    'version' => '0.1.0',
    'state' => 'beta',
    'clearcacheonload' => 0,
    'author' => 'Thomas Heilmann',
    'author_email' => 'heilmann@mindscreen.de',
    'author_company' => 'mindscreen GmbH',
    'constraints' => array(
        'depends' => array(
            'typo3' => '6.2.0-7.9.99',
        ),
        'conflicts' => array(
        ),
        'suggests' => array(
        ),
    ),
);
