<?php

namespace NSWDPC\UserForms\IpCollection\Tests;

use SilverStripe\Dev\SapphireTest;
use NSWDPC\UserForms\IpCollection\IP;

class IpTest extends SapphireTest {

    /**
     * @var bool
     */
    protected $usesDatabase = false;

    protected $ra, $cf, $xff = null;

    public function setUp() {
        parent::setUp();

        // store original values
        if(isset($_SERVER['REMOTE_ADDR'])) {
            $this->ra = $_SERVER['REMOTE_ADDR'];
        }
        if(isset($_SERVER['HTTP_CF_CONNECTING_IP'])) {
            $this->cf = $_SERVER['HTTP_CF_CONNECTING_IP'];
        }
        if(isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $this->xff = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }
    }

    public function tearDown() {
        parent::tearDown();

        // reset values
        if($this->ra) {
            $_SERVER['REMOTE_ADDR'] = $this->ra;
        }
        if($this->cf) {
            $_SERVER['HTTP_CF_CONNECTING_IP'] = $this->cf;
        }
        if($this->xff) {
            $_SERVER['HTTP_X_FORWARDED_FOR'] = $this->xff;
        }
    }

    /**
     * Test IP priority logic
     */
    public function testIpPriority() {

        $cf = 'a.b.c.d';
        $xff = '1.2.3.4';
        $ra = 'r.em.ot.e';

        $_SERVER['HTTP_CF_CONNECTING_IP'] = $cf;
        $_SERVER['HTTP_X_FORWARDED_FOR'] = $xff;
        $_SERVER['REMOTE_ADDR'] = $ra;

        $ip = IP::getByPriority();

        $this->assertEquals($cf, $ip);

    }

    /**
     * Test IP fallback logic
     */
    public function testIpFallback() {

        $cf = '';
        $xff = '';
        $ra = 'original';

        $_SERVER['HTTP_CF_CONNECTING_IP'] = $cf;
        $_SERVER['HTTP_X_FORWARDED_FOR'] = $xff;
        $_SERVER['REMOTE_ADDR'] = $ra;

        $ip = IP::getByPriority();

        $this->assertEquals($ra, $ip);

    }

    /**
     * Test untrusted data
     */
    public function testIpClean() {

        $cf = '127.0.0.1,<a href="naughty">click here!</a>';
        $xff = '';
        $ra = 'original';

        $_SERVER['HTTP_CF_CONNECTING_IP'] = $cf;
        $_SERVER['HTTP_X_FORWARDED_FOR'] = $xff;
        $_SERVER['REMOTE_ADDR'] = $ra;

        $ip = IP::getByPriority();

        $expected = "127.0.0.1,click here!";

        $this->assertEquals($expected, $ip);

    }
}
