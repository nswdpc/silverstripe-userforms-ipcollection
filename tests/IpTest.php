<?php

namespace NSWDPC\UserForms\IpCollection\Tests;

use SilverStripe\Control\Middleware\TrustedProxyMiddleware;
use SilverStripe\Control\Controller;
use SilverStripe\Control\HTTPRequest;
use SilverStripe\Core\Injector\Injector;
use SilverStripe\Dev\SapphireTest;
use NSWDPC\UserForms\IpCollection\IP;

class IpTest extends SapphireTest
{
    /**
     * @var bool
     */
    protected $usesDatabase = false;

    #[\Override]
    protected function setUp(): void
    {
        parent::setUp();

        // default request headers
        $trustedProxyMiddleware = Injector::inst()->get(TrustedProxyMiddleware::class);
        $trustedProxyMiddleware->setProxyIPHeaders([
            'Client-IP',
            'X-Forwarded-For'
        ]);
        $trustedProxyMiddleware->setTrustedProxyIPs('*');// allow trusted proxy configuration to be used
    }

    /**
     * Test IP priority logic
     */
    public function testIpPriority(): void
    {

        $trustedProxyMiddleware = Injector::inst()->get(TrustedProxyMiddleware::class);
        $headers = [
            'CF-Connecting-IP',// highest priority
            'X-Forwarded-For'
        ];
        $trustedProxyMiddleware->setProxyIPHeaders($headers);

        $controller = Controller::curr();

        // some valid non-private, non-reserved headers for a test
        $cf = '1.1.1.1';
        $xff = '1.0.0.1';

        $request = $controller->getRequest();
        $request->addHeader('CF-Connecting-IP', $cf);
        $request->addHeader('X-Forwarded-For', $xff);

        $delegate = function (HTTPRequest $request): void {
        };
        $trustedProxyMiddleware->process($request, $delegate);
        $ip = IP::getFromRequest($controller);
        $this->assertEquals($cf, $ip);

    }

    /**
     * Test IP fallback logic
     */
    public function testIpFallback(): void
    {

        $original = '8.8.8.8';
        $trustedProxyMiddleware = Injector::inst()->get(TrustedProxyMiddleware::class);

        $controller = Controller::curr();
        $request = $controller->getRequest();
        $request->setIp($original);
        $request->removeHeader('CF-Connecting-IP');
        $request->removeHeader('X-Forwarded-For');

        $delegate = function (HTTPRequest $request): void {
        };
        $trustedProxyMiddleware->process($request, $delegate);
        $ip = IP::getFromRequest($controller);
        $this->assertEquals($original, $ip);

    }

    /**
     * Test invalid data
     */
    public function testIpClean(): void
    {

        $trustedProxyMiddleware = Injector::inst()->get(TrustedProxyMiddleware::class);
        $headers = [
            'CF-Connecting-IP',// highest priority
            'X-Forwarded-For'
        ];
        $trustedProxyMiddleware->setProxyIPHeaders($headers);

        $controller = Controller::curr();

        $original = '8.8.8.8';
        $cf = 'invalid cf value';
        $xff = 'invalid xff value';

        $request = $controller->getRequest();
        $request->setIp($original);
        $request->addHeader('CF-Connecting-IP', $cf);
        $request->addHeader('X-Forwarded-For', $xff);

        $delegate = function (HTTPRequest $request): void {
        };
        $trustedProxyMiddleware->process($request, $delegate);
        $ip = IP::getFromRequest($controller);
        $this->assertEquals($original, $ip);

    }
}
