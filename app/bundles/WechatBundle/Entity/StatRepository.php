<?php
namespace Mautic\WechatBundle\Entity;

use Doctrine\ORM\NoResultException;
use Mautic\CoreBundle\Entity\CommonRepository;

/**
 * Class StatRepository
 *
 * @package Mautic\WechatBundle\Entity
 */
class StatRepository extends CommonRepository
{
    /**
     * @param      $smsId
     * @param null $listId
     *
     * @return array
     */
    public function getStats($Type)
    {
        $q = $this->_em->getConnection()->createQueryBuilder();
        $q->select('stat.id')
            ->from(MAUTIC_TABLE_PREFIX . 'wechat_stats', 'stat')
            ->where('stat.id = :id')
            ->setParameter('id', $id);

        $result = $q->execute()->fetchAll();

        return $result;
    }

    /**
     * @return string
     */
    public function getTableAlias()
    {
        return 'stat';
    }

}

