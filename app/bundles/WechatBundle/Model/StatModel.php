<?php
/**
 * @package     Mautic
 * @copyright   2014 Mautic Contributors. All rights reserved.
 * @author      Mautic
 * @link        http://mautic.org
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

namespace Mautic\WechatBundle\Model;

use Mautic\CoreBundle\Helper\DateTimeHelper;
use Mautic\CoreBundle\Helper\GraphHelper;
use Mautic\CoreBundle\Model\FormModel;
use Mautic\WechatBundle\Swiftmailer\Exception\BatchQueueMaxException;
use Mautic\WechatBundle\Entity\DoNotWechat;
use Mautic\WechatBundle\Entity\Account;
use Mautic\WechatBundle\Entity\Stat;
use Mautic\WechatBundle\Event\WechatBuilderEvent;
use Mautic\WechatBundle\Event\WechatEvent;
use Mautic\WechatBundle\Event\WechatOpenEvent;
use Mautic\WechatBundle\WechatEvents;
use Mautic\LeadBundle\Entity\DoNotContact;
use Mautic\LeadBundle\Entity\Lead;
use Mautic\CoreBundle\Helper\Chart\LineChart;
use Mautic\CoreBundle\Helper\Chart\PieChart;
use Mautic\CoreBundle\Helper\Chart\ChartQuery;
use Doctrine\DBAL\Query\QueryBuilder;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

/**
 * Class WechatModel
 * {@inheritdoc}
 * @package Mautic\CoreBundle\Model\FormModel
 */
class StatModel extends FormModel
{
    /**
     * {@inheritdoc}
     *
     * @return \Mautic\WechatBundle\Entity\AccountRepository
     */
    public function getRepository ()
    {
        return $this->em->getRepository('MauticWechatBundle:Stat');
    }

    /**
     * @return \Mautic\WechatBundle\Entity\StatRepository
     */
    public function getStatRepository ()
    {
        return $this->factory->getEntityManager()->getRepository('MauticWechatBundle:Stat');
    }

    /**
     * {@inheritdoc}
     */
    public function getPermissionBase ()
    {
        return 'wechat:wechats';
    }

    /**
     * {@inheritdoc}
     *
     * @param Wechat $entity
     * @param       $unlock
     *
     * @return mixed
     */
    public function saveEntity ($entity, $unlock = true)
    {
        $now = new DateTimeHelper();

        //set the author for new pages
        if (!$entity->isNew()) {
            //increase the revision
            $revision = $entity->getRevision();
            $revision++;
            $entity->setRevision($revision);
        }

        $changes = $entity->getChanges();
        // $parent  = $entity->getVariantParent();
        //
        // if ($parent !== null && !empty($changes) && empty($this->inConversion)) {
        //     $entity->setVariantSentCount(0);
        //     $entity->setVariantReadCount(0);
        //     $entity->setVariantStartDate($now->getDateTime());
        // }

        parent::saveEntity($entity, $unlock);

        // If parent, add this entity as a child of the parent so that it populates the list in the tab (due to Doctrine hanging on to entities in memory)
        // if ($parent) {
        //     $parent->addVariantChild($entity);
        // }

        // Reset associated variants if applicable due to changes
        // if ($entity->isVariant() && !empty($changes) && empty($this->inConversion)) {
        //     $dateString = $now->toUtcString();
        //     $parentId = (!empty($parent)) ? $parent->getId() : $entity->getId();
        //     $this->getRepository()->resetVariants($parentId, $dateString);
        //
        //     //if the parent was changed, then that parent/children must also be reset
        //     if (isset($changes['variantParent'])) {
        //         $this->getRepository()->resetVariants($changes['variantParent'][0], $dateString);
        //     }
        // }
    }

    /**
     * Save an array of entities
     *
     * @param  $entities
     * @param  $unlock
     *
     * @return array
     */
    public function saveEntities ($entities, $unlock = true)
    {
        //iterate over the results so the events are dispatched on each delete
        $batchSize = 20;
        foreach ($entities as $k => $entity) {
            $isNew = ($entity->getId()) ? false : true;

            //set some defaults
            $this->setTimestamps($entity, $isNew, $unlock);

            if ($dispatchEvent = $entity instanceof Wechat) {
                $event = $this->dispatchEvent("pre_save", $entity, $isNew);
            }

            $this->getRepository()->saveEntity($entity, false);

            if ($dispatchEvent) {
                $this->dispatchEvent("post_save", $entity, $isNew, $event);
            }

            if ((($k + 1) % $batchSize) === 0) {
                $this->em->flush();
            }
        }
        $this->em->flush();
    }

    /**
     * Delete an entity
     *
     * @param object $entity
     *
     * @return void
     */
    public function deleteEntity($entity)
    {
        $this->getRepository()->nullVariantParent($entity->getId());

        return parent::deleteEntity($entity);
    }

    /**
     * Delete an array of entities
     *
     * @param array $ids
     *
     * @return array
     */
    public function deleteEntities($ids)
    {
        $this->getRepository()->nullVariantParent($ids);

        return parent::deleteEntities($ids);
    }

    /**
     * {@inheritdoc}
     *
     * @param       $entity
     * @param       $formFactory
     * @param null  $action
     * @param array $options
     *
     * @return mixed
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
     public function createForm ($entity, $formFactory, $action = null, $options = array())
     {
         if (!$entity instanceof Stat) {
             throw new MethodNotAllowedHttpException(array('Stat'));
         }
         if (!empty($action)) {
             $options['action'] = $action;
         }
         return $formFactory->create('stat', $entity, $options);
     }

    /**
     * Get a specific entity or generate a new one if id is empty
     *
     * @param $id
     *
     * @return null|Wechat Stat
     */
    public function getEntity ($id = null)
    {
        if ($id === null) {
            $entity = new Stat;
        } else {
            $entity = parent::getEntity($id);
        }

        return $entity;
    }

    public function processWechatEvent($stat, $request) {
        $event = new WechatEvent($stat, $request);

        $eventType = $event->getEventType();

        if ($eventType == 'account_followed') {

        } else if ($eventType == 'message_received') {

        } else if ($eventType == 'article_opened') {

        } else if ($eventType == 'article_shared') {

        } else {

        }

        $this->getRepository()->saveEntity($stat);
    }

}
