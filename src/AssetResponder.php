<?php
namespace Aura\Asset_Bundle;

use Aura\Web\Response;
use SplFileObject;

/**
 *
 * Builds an asset response.
 *
 */
class AssetResponder
{
    /**
     *
     * A web response object.
     *
     * @var Response
     *
     */
    protected $response;

    /**
     *
     * Constructor.
     *
     * @param Response $response A web response object.
     *
     */
    public function __construct(Response $response)
    {
        $this->response = $response;
    }

    /**
     *
     * Modifies and returns the response.
     *
     * @param string $path The filesystem path to the asset.
     *
     * @param string $type The asset media type.
     *
     * @return Response
     *
     */
    public function __invoke($path, $type)
    {
        if ($path) {
            $this->ok($path, $type);
        } else {
            $this->notFound();
        }
        return $this->response;
    }

    /**
     *
     * Sets a 200 OK response with the asset contents.
     *
     * @param string $path The filesystem path to the asset.
     *
     * @param string $type The asset media type.
     *
     * @return null
     *
     */
    protected function ok($path, $type)
    {
        $this->response->status->set(200);
        $this->response->content->set(function () use ($path) {
            $file = new SplFileObject($path);
            while (! $file->eof()) {
                echo $file->fgets();
            }
        });
        $this->response->content->setType($type);
    }

    /**
     *
     * Sets a 404 Not Found response.
     *
     * @param string $path The filesystem path to the asset.
     *
     * @param string $type The asset media type.
     *
     * @return null
     *
     */
    protected function notFound()
    {
        $this->response->status->set(404);
    }
}
