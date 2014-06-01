<?php
namespace Aura\Asset\Web;

use Aura\Web_Kernel\AbstractController;

class AssetController extends AbstractController
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

    protected $vendor_path;

    protected $format_types;

    public function setVendorPath($vendor_path)
    {
        $this->vendor_path= $vendor_path;
    }

    public function setFormatTypes($format_types)
    {
        $this->format_types = $format_types;
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

    public function actionIndex($vendor = null, $package = null, $file = null, $format = null)
    {
        $config_mode = 'dev';

        $fakepath = $this->vendor_path . DIRECTORY_SEPARATOR .
            $vendor . DIRECTORY_SEPARATOR .
            $package . "/web/{$file}{$format}";

        $realpath = realpath($fakepath);

        // does the asset file exist?
        if (! file_exists($realpath) || ! is_readable($realpath)) {
            $content = "Asset not found: "
                     . htmlspecialchars($fakepath, ENT_QUOTES, 'UTF-8');
            $this->response->status->set('404', 'Not Found', '1.1');
            $this->response->content->set($content);
            return;
        }

        // are we in a config mode that wants us to cache?
        // $this->context->getEnv('AURA_CONFIG_MODE', 'default');
        if (in_array($config_mode, $this->cache_config_modes)) {
            // copy source to this target cache location
            $path = $this->web_cache_dir . DIRECTORY_SEPARATOR
                  . $vendor . DIRECTORY_SEPARATOR
                  . $package . DIRECTORY_SEPARATOR
                  . $file . $format;

            $webcache = dirname(dirname(__DIR__)) . $path;

            // make sure we have a dir for it
            $dir = dirname($webcache);
            if (! is_dir($dir)) {
                @mkdir($dir, 0755, true);
            }

            // copy from the source package to the target cache dir for the
            // next time this package asset is requested
            copy($realpath, $webcache);
        }

        // open the asset file using a shared (read) lock
        $fh = fopen($realpath, 'rb');
        $size = filesize($realpath);
        $contents = fread($fh, $size);
        fclose($fh);
        $this->response->headers->set('Content-Type', $this->format_types->getContentType($format));
        // set the response content to the file handle
        $this->response->content->set($contents);
    }
}
