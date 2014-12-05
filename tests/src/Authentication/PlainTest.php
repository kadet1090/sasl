<?php

namespace Fabiang\Sasl\Authentication;

/**
 * Generated by PHPUnit_SkeletonGenerator on 2014-12-05 at 13:28:55.
 *
 * @coversDefaultClass Fabiang\Sasl\Authentication\Plain
 */
class PlainTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var Plain
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->object = new Plain;
    }

    /**
     * @covers ::getResponse
     */
    public function testGetResponse()
    {
        $this->assertSame("zid\x0test\x0pass", $this->object->getResponse('test', 'pass', 'zid'));
    }
}
