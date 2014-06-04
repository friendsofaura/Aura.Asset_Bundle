<?php
namespace Aura\Asset_Bundle\Domain;

use Aura\Asset_Bundle\Exception\NotFound;

class AssetService
{
    /**
     *
     * The Aura config modes in which we should cache web assets.
     *
     * @var array
     *
     */
    protected $cache_config_modes = [];

    /**
     *
     * The subdirectory inside the web document root where we should cache
     * web assets.
     *
     * @var array
     *
     */
    protected $web_cache_dir;

    /**
     *
     * The path to vendor useful for testing
     *
     * @var string $vendor_path
     *
     */
    protected $vendor_path;

    /**
     *
     * Constructor.
     *
     * @param Request $request A web request object.
     *
     * @param Response $response A web response object.
     *
     */
    public function __construct(
        array $vendor_paths,
        $web_cache_dir,
        $config_mode = 'prod',
        $cache_config_modes = array()
    ) {
        $this->vendor_paths = $vendor_paths;
        $this->web_cache_dir = $web_cache_dir;
        $this->config_mode = $config_mode;
        $this->cache_config_modes = $cache_config_modes;
    }

    public function addVendorPaths($vendor_paths)
    {
        foreach ($vendor_paths as $prefix => $path) {

            $this->addVendorPath($prefix, $path);
        }
    }

    public function addVendorPath($prefix, $path)
    {
        $this->vendor_paths[$prefix] = $path;
    }

    /**
     *
     * Sets the config modes in which caching should take place.
     *
     * @param array $modes An array of mode names.
     *
     * @return void
     *
     */
    public function setCacheConfigModes(array $modes = [])
    {
        $this->cache_config_modes = $modes;
    }

    /**
     *
     * Sets the current config mode
     *
     * @param string $config_mode The current mode
     *
     * @return void
     *
     */
    public function setCacheConfigMode($config_mode)
    {
        $this->config_mode = $config_mode;
    }

    /**
     *
     * Sets the subdirectory in the web document root where web assets should
     * be cached.
     *
     * @param string $dir
     *
     * @return void
     *
     */
    public function setWebCacheDir($dir)
    {
        $this->web_cache_dir = $dir;
    }

    public function getAssetPath($vendor, $package, $file, $format)
    {
        $prefix = $vendor . '/' . $package;
        $realpath = null;
        if (isset($this->vendor_paths[$prefix])) {
            $fakepath = $this->vendor_paths[$prefix] .
                DIRECTORY_SEPARATOR . "{$file}{$format}";
            $realpath = realpath($fakepath);
        }
        return $realpath;
    }

    public function cache($realpath, $vendor, $package, $file, $format)
    {
        if (! $this->isReadable($realpath)) {
            throw new NotFound("Not Found in path " + $realpath);
        }
        // are we in a config mode that wants us to cache?
        if (in_array($this->config_mode, $this->cache_config_modes)) {
            // copy source to this target cache location
            $webcache = $this->web_cache_dir . DIRECTORY_SEPARATOR
                  . $vendor . DIRECTORY_SEPARATOR
                  . $package . DIRECTORY_SEPARATOR
                  . $file . $format;

            // make sure we have a dir for it
            $dir = dirname($webcache);
            if (! is_dir($dir)) {
                @mkdir($dir, 0755, true);
            }

            // copy from the source package to the target cache dir for the
            // next time this package asset is requested
            copy($realpath, $webcache);
        }
    }

    public function isReadable($realpath)
    {
        // does the asset file exist?
        if (file_exists($realpath) && is_readable($realpath)) {
            return true;
        }
        return false;
    }

    public function readFile($realpath)
    {
        if (! $this>isReadable($realpath)) {
            throw new NotFound("Not Found in path " + $realpath);
        }
        $content = function () use ($realpath) {
            $file = new SplFileObject($realpath);
            while (!$file->eof()) {
                echo $file->fgets();
            }
        };
        return $content;
    }
}
