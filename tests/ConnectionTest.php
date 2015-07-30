<?php

namespace Adldap\Tests;

use Adldap\Connections\Ldap;

class ConnectionTest extends FunctionalTestCase
{
    /**
     * This tests that no exception is thrown when trying
     * to perform an LDAP method while suppressing errors.
     */
    public function testConnectionSuppressErrors()
    {
        $ldap = new Ldap();

        $ldap->suppressErrors();

        $this->assertFalse($ldap->bind('test', 'test'));
    }

    /**
     * This tests that an exception is thrown
     * when trying to perform an LDAP method while
     * showing errors.
     */
    public function testConnectionShowErrors()
    {
        $ldap = new Ldap();

        $ldap->showErrors();

        $ldap->connect('test');

        try
        {
            $ldap->bind('test', 'test');

            $passes = false;
        } catch(\Exception $e)
        {
            $passes = true;
        }

        $this->assertTrue($passes);
    }

    public function testEscapeManual()
    {
        $ldap = $this->mock('Adldap\Connections\Ldap')->makePartial();

        $ldap->shouldAllowMockingProtectedMethods(true);

        $expected = '\74\65\73\74\69\6e\67\3d\2b\3c\3e\22\22\3b\3a\23\28\29*\5c\78\30\30';

        $result = $ldap->escapeManual('testing=+<>"";:#()*\x00', '*');

        $this->assertEquals($expected, $result);
    }

    public function testEscapeManualFilter()
    {
        $ldap = $this->mock('Adldap\Connections\Ldap')->makePartial();

        $ldap->shouldAllowMockingProtectedMethods(true);

        $expected = 'testing=+<>"";:#\28\29*\5cx00';

        $result = $ldap->escapeManual('testing=+<>"";:#()*\x00', '*', 1);

        $this->assertEquals($expected, $result);
    }

    public function testEscapeManualDn()
    {
        $ldap = $this->mock('Adldap\Connections\Ldap')->makePartial();

        $ldap->shouldAllowMockingProtectedMethods(true);

        $expected = 'testing\3d\2b\3c\3e\22\22\3b:\23()*\5cx00';

        $result = $ldap->escapeManual('testing=+<>"";:#()*\x00', '*', 2);

        $this->assertEquals($expected, $result);
    }

    public function testEscapeManualBothFilterAndDn()
    {
        $ldap = $this->mock('Adldap\Connections\Ldap')->makePartial();

        $ldap->shouldAllowMockingProtectedMethods(true);
        
        // echo ldap_escape('testing=+<>"";:#()*\x00');
        // results in 'testing\3d\2b\3c\3e\22\22\3b:\23\28\29*\5cx00'
        $expected = 'testing\3d\2b\3c\3e\22\22\3b:\23\28\29*\5cx00';

        $result = $ldap->escapeManual('testing=+<>"";:#()*\x00', '*', 3);

        $this->assertEquals($expected, $result);
    }

    /**
     * Where tests specific to manually
     * escaping strings if ldap_escape()
     * is not available.
     * 
     * Edits made to src/Connections/Ldap.php
     * lines 747 & 793
     */
    public function testEscapeManualNoIgnoreFlag0()
    {
        $stringToEscape = 'abc123\*(),=+<>;"#';
        $expectedOutput = '\61\62\63\31\32\33\5c\2a\28\29\2c\3d\2b\3c\3e\3b\22\23';
        $flags =0;
        $ignore = '';

        $ldap = $this->mock('Adldap\Connections\Ldap')->makePartial();
        $ldap->shouldAllowMockingProtectedMethods(true);
        $result = $ldap->escapeManual($stringToEscape, $ignore, $flags);
        $this->assertEquals($expectedOutput, $result);
    }

    public function testEscapeManualNoIgnoreFlag1()
    {
        $stringToEscape = 'abc123\*(),=+<>;"#';
        $expectedOutput = 'abc123\5c\2a\28\29,=+<>;"#';
        $flags =1;
        $ignore = '';

        $ldap = $this->mock('Adldap\Connections\Ldap')->makePartial();
        $ldap->shouldAllowMockingProtectedMethods(true);
        $result = $ldap->escapeManual($stringToEscape, $ignore, $flags);
        $this->assertEquals($expectedOutput, $result);
    }

    public function testEscapeManualNoIgnoreFlag2()
    {
        $stringToEscape = 'abc123\*(),=+<>;"#';
        $expectedOutput = 'abc123\5c*()\2c\3d\2b\3c\3e\3b\22\23';
        $flags =2;
        $ignore = '';

        $ldap = $this->mock('Adldap\Connections\Ldap')->makePartial();
        $ldap->shouldAllowMockingProtectedMethods(true);
        $result = $ldap->escapeManual($stringToEscape, $ignore, $flags);
        $this->assertEquals($expectedOutput, $result);
    }

    public function testEscapeManualNoIgnoreFlag3()
    {
        $stringToEscape = 'abc123\*(),=+<>;"#';
        $expectedOutput = 'abc123\5c\2a\28\29\2c\3d\2b\3c\3e\3b\22\23';
        $flags =3;
        $ignore = '';

        $ldap = $this->mock('Adldap\Connections\Ldap')->makePartial();
        $ldap->shouldAllowMockingProtectedMethods(true);
        $result = $ldap->escapeManual($stringToEscape, $ignore, $flags);
        $this->assertEquals($expectedOutput, $result);
    }

    public function testEscapeManualIgnore321Flag0()
    {
        $stringToEscape = 'abc123\*(),=+<>;"#';
        $expectedOutput = '\61\62\63123\5c\2a\28\29\2c\3d\2b\3c\3e\3b\22\23';
        $flags =0;
        $ignore = '321';

        $ldap = $this->mock('Adldap\Connections\Ldap')->makePartial();
        $ldap->shouldAllowMockingProtectedMethods(true);
        $result = $ldap->escapeManual($stringToEscape, $ignore, $flags);
        $this->assertEquals($expectedOutput, $result);
    }

    public function testEscapeManualIgnore321Flag1()
    {
        $stringToEscape = 'abc123\*(),=+<>;"#';
        $expectedOutput = 'abc123\5c\2a\28\29,=+<>;"#';
        $flags =1;
        $ignore = '321';

        $ldap = $this->mock('Adldap\Connections\Ldap')->makePartial();
        $ldap->shouldAllowMockingProtectedMethods(true);
        $result = $ldap->escapeManual($stringToEscape, $ignore, $flags);
        $this->assertEquals($expectedOutput, $result);
    }

    public function testEscapeManualIgnore321Flag2()
    {
        $stringToEscape = 'abc123\*(),=+<>;"#';
        $expectedOutput = 'abc123\5c*()\2c\3d\2b\3c\3e\3b\22\23';
        $flags =2;
        $ignore = '321';

        $ldap = $this->mock('Adldap\Connections\Ldap')->makePartial();
        $ldap->shouldAllowMockingProtectedMethods(true);
        $result = $ldap->escapeManual($stringToEscape, $ignore, $flags);
        $this->assertEquals($expectedOutput, $result);
    }

    public function testEscapeManualIgnore321Flag3()
    {
        $stringToEscape = 'abc123\*(),=+<>;"#';
        $expectedOutput = 'abc123\5c\2a\28\29\2c\3d\2b\3c\3e\3b\22\23';
        $flags =3;
        $ignore = '321';

        $ldap = $this->mock('Adldap\Connections\Ldap')->makePartial();
        $ldap->shouldAllowMockingProtectedMethods(true);
        $result = $ldap->escapeManual($stringToEscape, $ignore, $flags);
        $this->assertEquals($expectedOutput, $result);
    }
}
