<?php

/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Company;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use User\Model\UserModel;

class Module {

    public function onBootstrap(MvcEvent $e) {
        $eventManager = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);

        $userModel = new UserModel();
        $userModel->initialize($e->getApplication()->getServiceManager());
        $uri = $e->getRequest()->getUri()->getPath();

        $companyModel = new \Company\Model\CompanyModel();
        $companyModel->initialize($e->getApplication()->getServiceManager());
        $companyDomain = $companyModel->getCompanyDomain();

        if (($companyDomain->getDomainType() === 'cfcc' || $companyDomain->getDomainType() === 'cfc') && $userModel->loggedIn() && \Core\Helper\Url::isRedirectUrl($uri) && !$userModel->getUserCompany()) {
            $response = $e->getResponse();
            $response->getHeaders()->addHeaderLine('Location', $companyDomain->getDomainType() === 'cfc' ? '/company/create-user-company' : '/company/create-corporate-user-company');
            $response->setStatusCode(302);
            $response->sendHeaders();
            exit;
        }
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

}
