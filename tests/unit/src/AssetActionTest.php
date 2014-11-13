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
        $responder = $this->action->__invoke('vendor', 'package', 'style.css');

        $this->assertInstanceOf('Aura\Asset_Bundle\AssetResponder', $responder);

        $actual = $responder->getData();
        $expect = (object) array(
            'asset' => (object) array(
                'path' => $this->asset_dir . DIRECTORY_SEPARATOR . 'style.css',
                'type' => 'text/css',
            )
        );
        $this->assertEquals($expect, $actual);
    }
}
