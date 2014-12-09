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

namespace Fabiang\Sasl;

use PHPUnit_Framework_TestCase as TestCase;

/**
 * Generated by PHPUnit_SkeletonGenerator on 2014-12-09 at 15:09:01.
 *
 * @coversDefaultClass Fabiang\Sasl\Sasl
 */
class SaslTest extends TestCase
{

    /**
     * @var Sasl
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->object = new Sasl;
    }

    /**
     * @param string $expectedInstance Expected object instance
     * @param string $mechanism        Authentication mechanism
     * @param string $hashAlgo         Expected hash alogrithm (for SCRAM)
     * @covers ::factory
     * @uses Fabiang\Sasl\Authentication\SCRAM::__construct
     * @uses Fabiang\Sasl\Authentication\SCRAM::getHashAlgo
     * @dataProvider provideMechanisms
     */
    public function testFactory($expectedInstance, $mechanism, $hashAlgo = null)
    {
        $object = $this->object->factory($mechanism);
        $this->assertInstanceOf($expectedInstance, $object);

        if (null !== $hashAlgo) {
            $this->assertInstanceOf('Fabiang\\Sasl\Authentication\\SCRAM', $object);
            $this->assertSame($hashAlgo, $object->getHashAlgo());
        }
    }

    /**
     * @covers ::factory
     * @expectedException Fabiang\Sasl\Exception\InvalidArgumentException
     * @expectedExceptionMessage Invalid SASL mechanism type 'test'
     */
    public function testUnknownSaslMechanism()
    {
        $this->object->factory('test');
    }

    /**
     * @return array
     */
    public function provideMechanisms()
    {
        return array(
            array(
                'expectedInstanc' => 'Fabiang\\Sasl\Authentication\\Anonymous',
                'mechanism'       => 'anonymous',
                'hashAlgo'        => null,
            ),
            array(
                'expectedInstanc' => 'Fabiang\\Sasl\Authentication\\Login',
                'mechanism'       => 'login',
                'hashAlgo'        => null,
            ),
            array(
                'expectedInstanc' => 'Fabiang\\Sasl\Authentication\\Plain',
                'mechanism'       => 'plain',
                'hashAlgo'        => null,
            ),
            array(
                'expectedInstanc' => 'Fabiang\\Sasl\Authentication\\External',
                'mechanism'       => 'external',
                'hashAlgo'        => null,
            ),
            array(
                'expectedInstanc' => 'Fabiang\\Sasl\Authentication\\CramMD5',
                'mechanism'       => 'crammd5',
                'hashAlgo'        => null,
            ),
            array(
                'expectedInstanc' => 'Fabiang\\Sasl\Authentication\\DigestMD5',
                'mechanism'       => 'digestmd5',
                'hashAlgo'        => null,
            ),
            array(
                'expectedInstanc' => 'Fabiang\\Sasl\Authentication\\DigestMD5',
                'mechanism'       => 'digest-md5',
                'hashAlgo'        => null,
            ),
            array(
                'expectedInstanc' => 'Fabiang\\Sasl\Authentication\\SCRAM',
                'mechanism'       => 'SCRAM-SHA-1',
                'hashAlgo'        => 'sha1',
            ),
            array(
                'expectedInstanc' => 'Fabiang\\Sasl\Authentication\\SCRAM',
                'mechanism'       => 'SCRAM-SHA1',
                'hashAlgo'        => 'sha1',
            ),
            array(
                'expectedInstanc' => 'Fabiang\\Sasl\Authentication\\SCRAM',
                'mechanism'       => 'SCRAM-SHA-256',
                'hashAlgo'        => 'sha256',
            ),
        );
    }
}
