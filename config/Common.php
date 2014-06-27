<?php
namespace Aura\Asset_Bundle\_Config;

use Aura\Di\Config;
use Aura\Di\Container;

class Common extends Config
{
    public function define(Container $di)
    {
        // set your configuration accordingly
        // $di->params['Aura\Asset_Bundle\AssetService']['types'] = array();
        // $di->params['Aura\Asset_Bundle\AssetService']['map'] = array();

        $di->params['Aura\Asset_Bundle\AssetResponder'] = array(
            'response' => $di->lazyGet('web_response'),
        );

        $di->params['Aura\Asset_Bundle\AssetAction'] = array(
            'domain' => $di->lazyNew('Aura\Asset_Bundle\AssetService'),
            'responder' => $di->lazyNew('Aura\Asset_Bundle\AssetResponder'),
        );
    }

    public function modify(Container $di)
    {
        $router = $di->get('web_router');
        $router->add('aura.asset', '/asset/{vendor}/{package}/{file}')
            ->setValues([
                'controller' => 'aura.asset',
            ])
            ->addTokens(array(
                'file' => '(.*)'
            ));

        $dispatcher = $di->get('web_dispatcher');
        $dispatcher->setObject(
            'aura.asset',
            $di->lazyNew('Aura\Asset_Bundle\AssetAction')
        );
    }
}
