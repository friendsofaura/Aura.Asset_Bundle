<?php
namespace Aura\Asset_Bundle\Domain;

class AssetServiceTest extends \PHPUnit_Framework_TestCase
{
    protected $service;

    public function setUp()
    {
        $web_cache_dir = ;
        $this->service = new AssetService(
            $web_cache_dir,
            $vendor_path,
            $config_mode,
            $cache_config_modes
        );
    }
}
