<?php
/**
 * @package     Mautic
 * @copyright   2016 Mautic Contributors. All rights reserved.
 * @author      Mautic
 * @link        http://mautic.org
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

namespace Mautic\WechatBundle\Entity;

use Doctrine\ORM\Tools\Pagination\Paginator;
use Mautic\CoreBundle\Entity\CommonRepository;
use Mautic\CoreBundle\Helper\DateTimeHelper;
use Mautic\CoreBundle\Helper\GraphHelper;

/**
 * Class StatRepository
 *
 * @package Mautic\WechatBundle\Entity
 */
class StatRepository extends CommonRepository
{
    /**
     * @param $trackingHash
     *
     * @return mixed
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getWechatStatus($trackingHash)
    {
        $q = $this->createQueryBuilder('s');
        $q->select('s')
            ->leftJoin('s.lead', 'l')
            ->leftJoin('s.account', 'a')
            ->where(
                $q->expr()->eq('s.trackingHash', ':hash')
            )
            ->setParameter('hash', $trackingHash);

        $result = $q->getQuery()->getResult();

        return (!empty($result)) ? $result[0] : null;
    }

    /**
     * Get a lead's wechat stat
     *
     * @param integer $leadId
     * @param array   $options
     *
     * @return array
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getLeadStats($leadId, array $options = array())
    {
        $query = $this->createQueryBuilder('s');

        $query->select('IDENTITY(s.account) AS account_id, s.id, s.dateSent, e.title, IDENTITY(s.list) AS list_id, l.name as list_name, s.trackingHash as idHash')
            ->leftJoin('MauticWechatBundle:Account', 'a', 'WITH', 'a.id = s.account')
            ->leftJoin('MauticLeadBundle:LeadList', 'l', 'WITH', 'l.id = s.list')
            ->where(
                $query->expr()->eq('IDENTITY(s.lead)', $leadId)
            );

        if (!empty($options['ipIds'])) {
            $query->orWhere('s.ipAddress IN (' . implode(',', $options['ipIds']) . ')');
        }

        if (isset($options['filters']['search']) && $options['filters']['search']) {
            $query->andWhere(
                $query->expr()->like('a.title', $query->expr()->literal('%' . $options['filters']['search'] . '%'))
            );
        }

        $stats = $query->getQuery()->getArrayResult();

        return $stats;
    }

    /**
     * Delete a stat
     *
     * @param $id
     */
    public function deleteStat($id)
    {
        $this->_em->getConnection()->delete(MAUTIC_TABLE_PREFIX . 'wechat_stats', array('id' => (int) $id));
    }

    /**
     * Fetch stats for some period of time.
     *
     * @param $wechatIds
     * @param $fromDate
     * @param $state
     *
     * @return mixed
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getWechatStats($accountIds, $fromDate, $state)
    {
        if (!is_array($accountIds)) {
            $accountIds = array((int) $accountIds);
        }

        // Load points for selected period
        $q = $this->createQueryBuilder('s');

        $q->select('s.id, 1 as data, s.dateSent as date');

        $q->where(
            $q->expr()->in('IDENTITY(s.account)', ':accounts')
        )
            ->setParameter('accounts', $accountIds);

        if ($state != 'sent') {
            $q->andWhere(
                $q->expr()->eq('s.is'.ucfirst($state), ':true')
            )
                ->setParameter('true', true, 'boolean');
        }

        $q->andwhere(
            $q->expr()->gte('s.dateSent', ':date')
        )
            ->setParameter('date', $fromDate)
            ->orderBy('s.dateSent', 'ASC');

        $stats = $q->getQuery()->getArrayResult();

        return $stats;
    }

    /**
     * @return string
     */
    public function getTableAlias()
    {
        return 's';
    }
}
