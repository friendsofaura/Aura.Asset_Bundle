<?php
namespace Aura\Asset_Bundle\_Config;

use Aura\Di\Config;
use Aura\Di\Container;

class Common extends Config
{
    public function define(Container $di)
    {
        $di->params['Aura\Asset_Bundle\Domain\AssetService'] = array(
            'vendor_paths' => array(

            ),
            'web_cache_dir' => dirname(__DIR__),
            'config_mode' => 'prod',
            'cache_config_modes' => array('staging', 'prod')
        );

        $di->params['Aura\Asset_Bundle\Responder\AssetResponder'] = array(
            'response' => $di->lazyGet('web_response'),
            'format_types' => $di->lazyNew('Aura\Asset\FormatTypes')
        );

        $di->params['Aura\Asset_Bundle\Action\AssetAction'] = array(
            'responder' => $di->lazyNew('Aura\Asset_Bundle\Responder\AssetResponder'),
            'asset_service' => $di->lazyNew('Aura\Asset_Bundle\Domain\AssetService')
        );
    }

    public function modify(Container $di)
    {
        $router = $di->get('web_router');

        $dispatcher = $di->get('web_dispatcher');

        $router->add('aura.asset', '/asset/{vendor}/{package}/{file}{format}')
            ->setValues([
                'controller' => 'aura.asset',
            ])
            ->addTokens(
                array(
                    'file' => '(.*?)',
                    'format' => '(\.[^/]+)?'
                )
            );

        $dispatcher->setObject(
            'aura.asset',
            $di->lazyNew('Aura\Asset_Bundle\Action\AssetAction')
        );
    }
}
