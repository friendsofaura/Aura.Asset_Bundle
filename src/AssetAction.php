<?php
namespace Aura\Asset_Bundle;

/**
 *
 * Maps an asset request to an asset response via the asset service.
 *
 */
class AssetAction
{
    /**
     *
     * A Responder to build the web response.
     *
     * @var AssetResponder
     *
     */
    protected $responder;

    /**
     *
     * A Domain object for assets.
     *
     * @var AssetService
     *
     */
    protected $domain;

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
        AssetService $domain,
        AssetResponder $responder
    ) {
        $this->domain = $domain;
        $this->responder = $responder;
    }

    /**
     *
     * Invokes the Domain and Responder to return a Response.
     *
     * @param string $vendor The vendor name.
     *
     * @param string $package The package name.
     *
     * @param string $file The asset file within within the vendor package.
     *
     * @return Response $response A web response object.
     *
     */
    public function __invoke($vendor, $package, $file)
    {
        $asset = $this->domain->getAsset($vendor, $package, $file);
        return $this->responder->__invoke($asset->path, $asset->type);
    }
}
