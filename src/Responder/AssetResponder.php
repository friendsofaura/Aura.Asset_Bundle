<?php
namespace Aura\Asset_Bundle\Responder;

use Aura\Web\Response;
use Aura\Asset_Bundle\Exception\NotFound;
use Aura\Asset_Bundle\FormatTypes;

class AssetResponder
{
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

    public function __construct(
        Response $response        
    ) {
        $this->response = $response;        
    }
    
    public function setContent()
    {
        $this->content = $content;
    }
    
    public function setContentType($content_type)
    {        
        $this->response->headers->set('Content-Type', $content_type);
    }
    
    public function setStatus($status_code, $status_message, $http_version = '1.1')
    {        
        $this->response->status->set($status_code, $status_message, $http_version);
    }
    
    public function __invoke()
    {
        $this->response->content->set($this->content);
    }
}
