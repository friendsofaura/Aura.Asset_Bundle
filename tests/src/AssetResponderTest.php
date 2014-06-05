<?php
namespace Aura\Asset_Bundle;

use Aura\Web\WebFactory;

class AssetResponderTest extends \PHPUnit_Framework_TestCase
{
    protected $responder;

    protected $asset_dir;

    public function setUp()
    {
        $this->asset_dir = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'web';

        $web_factory = new WebFactory($GLOBALS);
        $this->responder = new AssetResponder($web_factory->newResponse());
    }

    public function test__invoke_Ok()
    {
        $path = $this->asset_dir. DIRECTORY_SEPARATOR . 'style.css';
        $type = 'text/css';
        $response = $this->responder->__invoke($path, $type);

        $this->assertInstanceOf('Aura\Web\Response', $response);

        $actual = $response->status->getCode();
        $this->assertSame(200, $actual);

        $actual = $response->content->getType();
        $this->assertSame($type, $actual);

        $content = $response->content->get();
        ob_start();
        $content();
        $actual = ob_get_clean();

        $expect = file_get_contents($path);
        $this->assertSame($expect, $actual);
    }

    public function test__invoke_NotFound()
    {
        $path = null;
        $type = null;
        $response = $this->responder->__invoke($path, $type);

        $this->assertInstanceOf('Aura\Web\Response', $response);

        $actual = $response->status->getCode();
        $this->assertSame(404, $actual);

        $actual = $response->content->getType();
        $this->assertSame('', $actual);

        $content = $response->content->get();
        $this->assertSame('', $actual);
    }
}
