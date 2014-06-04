<?php
namespace Aura\Asset_Bundle\Domain;
use org\bovigo\vfs\vfsStream;

class AssetServiceTest extends \PHPUnit_Framework_TestCase
{
    protected $service;

    public function setUp()
    {
        $web_cache_dir = __DIR__;
        $vendor_paths = array(
            'aura/blog' => vfsStream::url('/path/to/vendor/aura/blog/web/')
        );
        $config_mode = 'prod';
        $cache_config_modes = array(
            'prod',
            'dev'
        );
        $this->service = new AssetService(
            $vendor_paths,
            $web_cache_dir,
            $config_mode,
            $cache_config_modes
        );
    }

    public function testGetAssetPath()
    {
        $vendor = 'aura';
        $package = 'blog';
        $file = 'css/hello';
        $format = '.css';
        $this->assertEquals(
            '/path/to/vendor/aura/blog/web/css/hello.css',
            $this->service->getAssetPath($vendor, $package, $file, $format)
        );
    }
}
