<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;

use Application\Factories\AdapterFactory;
use Zend\ModuleManager\Feature\ServiceProviderInterface;
use Zend\Mvc\I18n\Translator as MvcTraslator;
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\Validator\AbstractValidator;

class Module implements ServiceProviderInterface
{
    const VERSION = '3.0.3-dev';

    public function onBootstrap(MvcEvent $mvcEvent)
    {
        $eventManager = $mvcEvent->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);

        $result = $eventManager->getSharedManager();

        $result->attach('Zend\Mvc\Controller\AbstractActionController', 'dispatch', function ($e) {

        }, 100);

        (new AdapterFactory($mvcEvent->getApplication()->getServiceManager()));

    }

    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }

    /**
     * Expected to return \Zend\ServiceManager\Config object or array to
     * seed such an object.
     *
     * @return array|\Zend\ServiceManager\Config
     */
    public function getServiceConfig()
    {
    }
}
