<?php

namespace Company\Controller;

use Core\Model\ErrorModel;
use Core\Helper\Format;
use Core\Helper\Mautic;

class DefaultController extends \Core\Controller\CompanyController {

    public function checkPermission() {
        
    }

    public function indexAction() {
        if (ErrorModel::getErrors()) {
            return $this->_view;
        }

        $this->_view->company_tax = $this->_companyModel->getCompanyTax($this->_companyModel->getLoggedPeopleCompany()->getId());

        return $this->_view;
    }

    public function createUserCompanyAction() {
        if (ErrorModel::getErrors()) {
            return $this->_view;
        }
        $params = $this->params()->fromPost();
        if (!$this->_userModel->loggedIn()) {
            return \Core\Helper\View::redirectToLogin($this->_renderer, $this->getResponse(), $this->getRequest(), $this->redirect());
        } elseif ($params && $this->_userModel->loggedIn()) {
            $people = $this->_companyModel->addCompany($params);
            if ($people) {
                Mautic::addCompany($people);
                Mautic::addDefaultCompany($this->_companyModel->getDefaultCompany());
                Mautic::addContact($this->_userModel->getLoggedUserPeople(), 'new,client');
                Mautic::persist();

                $this->_view->setVariables(Format::returnData(array(
                            'company' => $people->getName(),
                            'alias' => $people->getAlias()
                )));
            }
        }
        return $this->_view;
    }

}
