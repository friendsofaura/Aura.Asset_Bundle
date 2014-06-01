<?php
namespace Aura\Asset\_Config;

use Aura\Di\Config;
use Aura\Di\Container;

class Common extends Config
{
    public function define(Container $di)
    {
        $di->setter['Aura\Asset\Web\AssetController'] = [
            'setWebCacheDir'      => 'cache/asset',
            'setCacheConfigModes' => ['prod', 'staging'],
            'setVendorPath' => dirname(dirname(dirname(__DIR__))),
            'setFormatTypes' => $di->lazyNew('Aura\Asset\FormatTypes')
        ];
    }

    public function modify(Container $di)
    {
        $router = $di->get('web_router');

        $dispatcher = $di->get('web_dispatcher');

        $router->add('aura.asset', '/asset/{vendor}/{package}/{file}{format}')
            ->setValues([
                'controller' => 'aura.asset',
                'action' => 'actionIndex',
            ])
            ->addTokens(
                array(
                    'file' => '(.*?)',
                    'format' => '(\.[^/]+)?'
                )
            );

        $dispatcher->setObject(
            'aura.asset',
            $di->lazyNew('Aura\Asset\Web\AssetController')
        );
    }
}
