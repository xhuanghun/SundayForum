<?php

namespace Sunday\UserBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * ServiceGradeRepository
 */
class ServiceGradeRepository extends EntityRepository
{
    /**
     * Find one ServiceGrade by the given criteria.
     *
     * @param array $criteria
     * @return ServiceGrade
     */
    public function findServiceGradeBy(array $criteria)
    {
        return $this->findOneBy($criteria);
    }

    /**
     * Get initial value for the beginning service grade of a certain BusinessUnit.
     *
     * @param string $BusinessUnitName
     * @return integer
     */
    public function getInitialValueByBusinessUnitName($BusinessUnitName)
    {
        return $this->findServiceGradeBy(array('BusinessUnitName' => $BusinessUnitName))->getInitialValue();
    }
}
