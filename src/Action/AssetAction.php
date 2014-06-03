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
        AssetService $asset_service,
        FormatTypes $format_types
    ) {        
        $this->responder = $responder;
        $this->asset_service = $asset_service;
        $this->format_types = $format_types;
    }
    
    public function __invoke($vendor = null, $package = null, $file = null, $format = null)
    {        
        $asset_path = $this->asset_service->getAssetPath($vendor, $package, $file, $format);
        try {
            $content = $this->asset_service->readFile($asset_path);
        } catch (NotFound $e) {
            $content = "Asset not found: " . $e>getMessage();
            $this->responder->setStatus('404', 'Not Found', '1.1');
        }
        $this->responder->setContent($content);        
        return $this->responder;
    }
}
