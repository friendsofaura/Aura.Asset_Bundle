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

    protected $data;

    public function __construct(
        Response $response,
        FormatTypes $format_types
    ) {
        $this->response = $response;
        $this->format_types = $format_types;
    }

    public function setData($data)
    {
        $this->data = (object) $data;
    }

    public function __invoke()
    {
        $responded = $this->notFound('asset')
                  || $this->responseView();

        if ($responded) {
            return $this->response;
        }
    }

    protected function notFound($key)
    {
        if (! $this->data->$key) {
            $this->response->status->set(404);
            return $this->response;
        }
    }

    protected function responseView()
    {
        $this->response->headers->set('Content-Type', $this->format_types->getContentType($this->data->format));
        $this->response->content->set($this->data->asset);
        return $this->response;
    }
}
