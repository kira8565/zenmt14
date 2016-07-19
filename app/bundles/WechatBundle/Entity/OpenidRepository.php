<?php
namespace Mautic\WechatBundle\Entity;

use Doctrine\ORM\NoResultException;
use Mautic\CoreBundle\Entity\CommonRepository;

/**
 * Class OpenidRepository
 */
class OpenidRepository extends CommonRepository
{
    /**
     * @param string $openId
     *
     * @return array
     */
    public function findByOpenId($openId)
    {
        $q = $this->createQueryBuilder('e')
            ->where('e.openId = :identifier')
            ->setParameter('identifier', strval($openId));

        return $q->getQuery()->getArrayResult();
    }

    /**
     * @return string
     */
    protected function getDefaultOrder()
    {
        return array(
            array('e.open_id', 'ASC')
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
