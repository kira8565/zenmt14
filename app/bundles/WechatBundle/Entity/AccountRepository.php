<?php
namespace Mautic\WechatBundle\Entity;

use Doctrine\ORM\NoResultException;
use Mautic\CoreBundle\Entity\CommonRepository;

/**
 * Class AccountRepository
 */
class AccountRepository extends CommonRepository
{

    /**
     * @param string $search
     * @param int    $limit
     * @param int    $start
     *
     * @return array
     */
    public function getAccountList($search = '', $limit = 10, $start = 0)
    {
        $q = $this->createQueryBuilder('e');
        $q->select('partial e.{id, name, description}');

        if (!empty($search)) {
            $q->andWhere($q->expr()->like('e.name', ':search'))
                ->setParameter('search', "{$search}%");
        }

        $q->orderBy('e.name');

        if (!empty($limit)) {
            $q->setFirstResult($start)
                ->setMaxResults($limit);
        }

        return $q->getQuery()->getArrayResult();
    }

    /**
     * @return string
     */
    protected function getDefaultOrder()
    {
        return array(
            array('e.name', 'ASC')
        );
    }

    /**
     * @return string
     */
    public function getTableAlias()
    {
        return 'e';
    }
}
