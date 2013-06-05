<?php

/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Contact;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;

class Module {

    public function onBootstrap(MvcEvent $e) {
        $eventManager = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
    }

    public function getConfig() {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig() {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

public function getServiceConfig() {
    return array(
        'factories' => array(
            'Contact\Model\Contact' => function($sm) {
                $db_adapter = $sm->get('db_adapter');
                $contact = new \Contact\Model\Contact($db_adapter);
                $log = $sm->get('Logger');
                $eventManager = $contact->getEventManager();

                $eventManager->attach('event.insert', function ($e) use ($log) {
                            $event = $e->getName();
                            $log->info("{$event} event triggered");
                        });

                $eventManager->attach('event.edit', function ($e) use ($log) {
                            $event = $e->getName();
                            $log->info("{$event} event triggered");
                        });

                $eventManager->attach('event.delete', function ($e) use ($log) {
                            $event = $e->getName();
                            $log->info("{$event} event triggered");
                        });

                return $contact;
            },
            'Logger' => function($sm) {
                $logger = new \Zend\Log\Logger;
                $writer = new \Zend\Log\Writer\Stream('data.log');

                $logger->addWriter($writer);
                return $logger;
            }
        ),
    );
}

    public function getViewHelperConfig() {
        return array(
            'invokables' => array(
                'error_msg' => 'Contact\View\Helper\ErrorMsg'
            ),
        );
    }

}
