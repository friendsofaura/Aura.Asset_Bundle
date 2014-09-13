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
            'response' => $di->lazyGet('aura/web-kernel:response'),
        );

        $di->params['Aura\Asset_Bundle\AssetAction'] = array(
            'domain' => $di->lazyNew('Aura\Asset_Bundle\AssetService'),
            'responder' => $di->lazyNew('Aura\Asset_Bundle\AssetResponder'),
        );
    }

    public function modify(Container $di)
    {
        $router = $di->get('aura/web-kernel:router');
        $router->add('aura.asset', '/asset/{vendor}/{package}/{file}')
            ->setValues([
                'action' => 'aura.asset',
            ])
            ->addTokens(array(
                'file' => '(.*)'
            ));

        $dispatcher = $di->get('aura/web-kernel:dispatcher');
        $dispatcher->setObject(
            'aura.asset',
            $di->lazyNew('Aura\Asset_Bundle\AssetAction')
        );
    }
}
