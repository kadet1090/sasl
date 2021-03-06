<?php

/**
 * Sasl library.
 *
 * Copyright (c) 2002-2003 Richard Heyes,
 *               2014 Fabian Grutschus
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions
 * are met:
 *
 * o Redistributions of source code must retain the above copyright
 *   notice, this list of conditions and the following disclaimer.
 * o Redistributions in binary form must reproduce the above copyright
 *   notice, this list of conditions and the following disclaimer in the
 *   documentation and/or other materials provided with the distribution.|
 * o The names of the authors may not be used to endorse or promote
 *   products derived from this software without specific prior written
 *   permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
 * A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
 * OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 * SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
 * LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * @author Fabian Grutschus <f.grutschus@lubyte.de>
 */

namespace Fabiang\Sasl\Behat;

use PHPUnit_Framework_Assert as Assert;
use Behat\Behat\Hook\Scope\BeforeScenarioScope;

/**
 * Defines application features from the specific context.
 *
 * @author f.grutschus
 */
abstract class AbstractContext
{

    protected $stream;
    protected $logdir;
    protected $logfile;

    protected function connect()
    {
        $errno  = null;
        $errstr = null;

        $this->stream = stream_socket_client("tcp://{$this->hostname}:{$this->port}", $errno, $errstr, 5);

        Assert::assertNotFalse($this->stream, "Coudn't connection to host {$this->hostname}");
    }

    /**
     * Read stream until string is found.
     *
     * @param string  $until
     * @param integer $timeout
     * @return string
     * @throws \Exception
     */
    protected function readStreamUntil($until, $timeout = 5)
    {
        $readStart = time();
        $data      = '';
        do {
            if (time() >= $readStart + $timeout) {
                throw new \Exception('Timeout when trying to receive buffer');
            }

            $data .= $this->read();
        } while (false === strpos($data, $until));

        return $data;
    }

    protected function read()
    {
        $data = fread($this->stream, 4096);
        fwrite($this->logfile, 'S: ' . trim($data) . "\n");
        return $data;
    }

    protected function write($data)
    {
        fwrite($this->stream, $data);
        fwrite($this->logfile, 'C: ' . trim($data) . "\n");
    }

    /**
     * @BeforeScenario
     */
    public function openLog(BeforeScenarioScope $scope)
    {
        $featureTags  = $scope->getFeature()->getTags();
        $mechanism    = array_shift($featureTags);
        $scenarioTags = $scope->getScenario()->getTags();
        $type         = array_shift($scenarioTags);

        $this->logfile = fopen(
            sprintf(
                '%s/behat.%s.%s.%s.log',
                $this->logdir,
                $mechanism,
                $type,
                time()
            ),
            'c'
        );
    }

    /**
     * @AfterScenario
     */
    public function closeConnection()
    {
        if ($this->stream) {
            fclose($this->stream);
        }

        fclose($this->logfile);
    }
}
