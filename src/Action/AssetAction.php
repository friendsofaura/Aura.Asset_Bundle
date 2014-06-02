<?php
namespace Aura\Asset_Bundle\Action;

use Aura\Web\Request;
use Aura\Web\Response;
use Aura\Asset_Bundle\Domain\AssetService;
use Aura\Asset_Bundle\Exception\NotFound;

class AssetAction
{
    /**
     * 
     * A web (not HTTP!) request object.
     * 
     * @var Request
     * 
     */
    protected $request;
    
    /**
     * 
     * A web (not HTTP!) response object.
     * 
     * @var Request
     * 
     */
    protected $response;
    
    /**
     * 
     * FormatTypes
     * 
     * @var $format_types;
     * 
     */
    protected $format_types;
    
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
        Request $request,
        Response $response,
        FormatTypes $format_types,
        AssetService $asset_service
    ) {
        $this->request = $request;
        $this->response = $response;
        $this->format_types = $format_types;
        $this->asset_service = $asset_service;
    }
    
    public function __invoke($vendor = null, $package = null, $file = null, $format = null)
    {
        try {
            $content = $this->asset_service->getAssetContents($vendor, $package, $file, $format);
            $this->response->headers->set('Content-Type', $this->format_types->getContentType($format));
        } catch (NotFound $e) {
            $content = "Asset not found: " . $e->getMessage();
            $this->response->status->set('404', 'Not Found', '1.1');            
        }
        $this->response->content->set($content);        
    }
}
