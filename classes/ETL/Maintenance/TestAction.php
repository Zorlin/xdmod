<?php
/* ==========================================================================================
 * Multi-use testing action that supports the following options:
 *  - Sleep for a number of seconds
 *  - Throw an exception
 *  - Echo a string
 *
 * @author Steve Gallo <smgallo@buffalo.edu>
 * @date 2016-12-20
 *
 * @see iAction
 * ==========================================================================================
 */

namespace ETL\Maintenance;

use ETL\aOptions;
use ETL\iAction;
use ETL\aAction;
use ETL\EtlConfiguration;
use ETL\EtlOverseerOptions;
use Log;

class TestAction extends aAction
implements iAction
{
    protected $options;
    public function __construct(aOptions $options, EtlConfiguration $etlConfig, Log $logger = null)
    {
        $requiredKeys = array("type");
        $this->verifyRequiredConfigKeys($requiredKeys, $options);
        parent::__construct($options, $etlConfig, $logger);
    }

    public function execute(EtlOverseerOptions $etlOptions)
    {
        // This should be made into a generic testing action that can
        // 1. Sleep
        // 2. Throw an exception
        // 3. Echo a string
        if ( ! isset($this->options->type) ) {
            return true;
        }

        if ( $etlOptions->isDryrun() ) {
            return;
        }

        switch ($this->options->type) {

            case 'sleep':
                $sleepSeconds = ( isset($this->options->sleep_seconds) ? $this->options->sleep_seconds : 60 );
                $this->logger->debug("Sleeping $sleepSeconds seconds");
                sleep($sleepSeconds);
                break;

            case 'exception':
                $msg = ( isset($this->options->exception_message) ? $this->options->exception_message : "" );
                $this->logger->debug("Throwing exception");
                $this->logAndThrowException($msg);
                break;

            case 'echo':
                $msg = ( isset($this->options->echo_message) ? $this->options->echo_message : "" );
                $this->logger->debug("Printing message '$msg'");
                print "$msg\n";
                break;

            default:
                $msg = "Unsupported type '" . $this->options->type . "'";
                $this->logAndThrowException($msg);
                break;
        }

        return true;
    }

    public function initialize()
    {
        return true;
    }

    public function verify(EtlOverseerOptions $etlConfig = null)
    {
        return true;
    }

    public function getName()
    {
        return $this->options->name;
    }

    public function getClass()
    {
        return $this->options->class;
    }

    public function getOptions()
    {
        return $this->options;
    }

    public function isVerified()
    {
        return true;
    }

}  // class TestAction
