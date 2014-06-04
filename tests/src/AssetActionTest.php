<?php
namespace Aura\Asset_Bundle;

use Aura\Web\WebFactory;

class AssetActionTest extends \PHPUnit_Framework_TestCase
{
    protected $action;

    protected $asset_dir;

    public function setUp()
    {
        $this->asset_dir = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'web';

        $service = new AssetService(array(
            'vendor/package' => $this->asset_dir,
        ));

        $web_factory = new WebFactory($GLOBALS);
        $responder = new AssetResponder($web_factory->newResponse());

        $this->action = new AssetAction($service, $responder);
    }

    public function test__invoke()
    {
        $response = $this->action->__invoke('vendor', 'package', 'style.css');

        $this->assertInstanceOf('Aura\Web\Response', $response);

        $actual = $response->status->getCode();
        $this->assertSame(200, $actual);

        $expect = 'text/css';
        $actual = $response->content->getType();
        $this->assertSame($expect, $actual);

        $content = $response->content->get();
        ob_start();
        $content();
        $actual = ob_get_clean();

        $path = $this->asset_dir. DIRECTORY_SEPARATOR . 'style.css';
        $expect = file_get_contents($path);
        $this->assertSame($expect, $actual);
    }
}
