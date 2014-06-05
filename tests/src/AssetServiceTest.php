<?php
namespace Aura\Asset_Bundle;

class AssetServiceTest extends \PHPUnit_Framework_TestCase
{
    protected $service;

    protected $asset_dir;

    public function setUp()
    {
        $this->asset_dir = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'web';
        $this->service = new AssetService(array(
            'vendor/package' => $this->asset_dir,
        ));
    }

    public function testGetAsset()
    {
        $asset = $this->service->getAsset('vendor', 'package', 'style.css');

        $expect = $this->asset_dir . DIRECTORY_SEPARATOR . 'style.css';
        $this->assertSame($expect, $asset->path);

        $expect = 'text/css';
        $this->assertSame($expect, $asset->type);
    }

    public function testGetAsset_noVendorPackage()
    {
        $asset = $this->service->getAsset('no-vendor', 'no-package', 'style.css');
        $this->assertNull($asset->path);
        $this->assertNull($asset->type);
    }

    public function testGetAsset_noType()
    {
        $asset = $this->service->getAsset('vendor', 'package', 'fake.txt');

        $expect = $this->asset_dir . DIRECTORY_SEPARATOR . 'fake.txt';
        $this->assertSame($expect, $asset->path);

        $this->assertNull($asset->type);
    }
}
