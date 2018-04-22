<?php

namespace Company\Model;

class CompanyModel extends \Core\Model\CompanyModel {

    /**
     * @return \Core\Entity\People
     */
    public function getCurrentPeopleCompany() {
        if ($this->getErrors()) {
            return;
        }
        return $this->getLoggedPeopleCompany();
    }

    public function getAllCompanies() {
        
    }

    public function addCompanyLink($entity_people, $currentPeopleCompany) {
        $people_employee = new \Core\Entity\PeopleEmployee();
        $people_employee->setCompany($entity_people);
        $people_employee->setEmployee($this->_userModel->getLoggedUser()->getPeople());
        $this->_em->persist($people_employee);

        $people_client = new \Core\Entity\ClientPeople();
        $people_client->setClient($entity_people);
        $people_client->setCompanyId($this->getDefaultCompany()->getId());
        $this->_em->persist($people_client);
    }

}
