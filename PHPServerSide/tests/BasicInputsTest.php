<?php

use PHPUnit\Framework\TestCase;
use App\Helpers;
use App\Task;

class BasicInputsTest extends TestCase
{
    public function testPermittedTypes()
    {
        $this->assertTrue(Helpers::isPermittedType('activity'));
        $this->assertTrue(Helpers::isPermittedType('appointment'));
        $this->assertTrue(Helpers::isPermittedType('meet'));
        $this->assertTrue(Helpers::isPermittedType('task'));

        $this->assertFalse(Helpers::isPermittedType('date'));
    }

    public function testStringInputs()
    {
        $this->assertTrue(Helpers::stringValid('Branding Logo'));
        $this->assertTrue(Helpers::stringValid('10.321'));
        $this->assertTrue(Helpers::stringValid('-1'));

        $this->assertFalse(Helpers::stringValid(0));
        $this->assertFalse(Helpers::stringValid(1));
        $this->assertFalse(Helpers::stringValid([]));
        $this->assertFalse(Helpers::stringValid(new Task('meet')));
    }

    public function testNumericInputs()
    {
        $this->assertTrue(Helpers::numericValid(0));
        $this->assertTrue(Helpers::numericValid(10));
        $this->assertTrue(Helpers::numericValid(10.321));
        $this->assertTrue(Helpers::numericValid('10.321'));
        $this->assertTrue(Helpers::numericValid(-1));
        $this->assertTrue(Helpers::numericValid('-1'));

        $this->assertFalse(Helpers::numericValid('Diez'));
    }

    public function testNumericGreaterThanCero()
    {
        $this->assertTrue(Helpers::greaterThanCero(10));
        $this->assertTrue(Helpers::greaterThanCero(10.321));
        $this->assertTrue(Helpers::greaterThanCero('10.321'));

        $this->assertFalse(Helpers::greaterThanCero(-1));
        $this->assertFalse(Helpers::greaterThanCero('-1'));
        $this->assertFalse(Helpers::greaterThanCero('Diez'));
        $this->assertFalse(Helpers::greaterThanCero(0));
    }
}
