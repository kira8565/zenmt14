<?php
namespace Mautic\WechatBundle\Entity;

use Doctrine\ORM\NoResultException;
use Mautic\CoreBundle\Entity\CommonRepository;

/**
 * Class MessageRepository
 *
 * @package Mautic\WechatBundle\Entity
 */
class MessageRepository extends CommonRepository
{
    /**
     * @param      $smsId
     * @param null $listId
     *
     * @return array
     */
    public function getMessages($Type)
    {
        $q = $this->_em->getConnection()->createQueryBuilder();
        $q->select('m.lead_id')
            ->from(MAUTIC_TABLE_PREFIX . 'wechat_messages', 'm')
            ->where('m.type = :type')
            ->setParameter('type', $Type);

        $result = $q->execute()->fetchAll();

        return $result;
    }

    /**
     * @return string
     */
    public function getTableAlias()
    {
        return 'm';
    }

}

