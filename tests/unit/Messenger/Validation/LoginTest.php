<?php

namespace Messenger\Validation;

class LoginTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider validLoginDataProvider
     */
    public function testShouldReturnTrueWhenLoginIsValid($validLogin)
    {
        $validator = new Login;

        $this->assertTrue($validator->validate($validLogin));
    }

    public function validLoginDataProvider()
    {
        return array(
            array('testttt'),
            array('test_test'),
            array('test.test')
        );
    }

    /**
     * @dataProvider invalidLoginDataProvider
     */
    public function testShouldReturnFalseWhenLoginIsInvalid($invalidLogin)
    {
        $validator = new Login;

        $this->assertFalse($validator->validate($invalidLogin));
    }

    public function invalidLoginDataProvider()
    {
        return array(
            array('123456'),
            array('this_is_a_really_huge_string'),
        );
    }
}
