<?php
namespace Aura\Asset_Bundle\Action;

use Aura\Web\Request;
use Aura\Asset_Bundle\Domain\AssetService;
use Aura\Asset_Bundle\Responder\AssetResponder;

class AssetAction
{
    /**
     *
     * A web responder
     *
     * @var AssetResponder
     *
     */
    protected $responder;

    /**
     *
     * Asset service
     *
     * @var AssetService
     *
     */
    protected $asset_service;

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
        AssetResponder $responder,
        AssetService $asset_service
    ) {
        $this->responder = $responder;
        $this->asset_service = $asset_service;
    }

    public function __invoke($vendor = null, $package = null, $file = null, $format = null)
    {
        $asset_path = $this->asset_service->getAssetPath($vendor, $package, $file, $format);
        try {
            $data['asset'] = $this->asset_service->readFile($asset_path);
            $data['format'] = $format;
            $this->responder->setData($data);
        } catch (NotFound $e) {
        }
        return $this->responder;
    }
}
