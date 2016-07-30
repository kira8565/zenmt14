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

use Mautic\WechatBundle\Entity\Account;
use Mautic\WechatBundle\Entity\Article;
use Mautic\WechatBundle\Entity\Message;
use Mautic\WechatBundle\Entity\News;
use Mautic\WechatBundle\Entity\Openid;
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
class WechatModel extends FormModel
{
    /**
     * @return \Mautic\WechatBundle\Entity\Repository
     */
    public function getRepository($type = null)
    {
        if (empty($type)){
            return $this->factory->getEntityManager()->getRepository('MauticWechatBundle:Account');
        }else{
            return $this->factory->getEntityManager()->getRepository('MauticWechatBundle:' . ucfirst($type));
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getPermissionBase ()
    {
        return 'wechat:wechats';
    }

    /**
     *
     * @param string $type, $id
     *
     * @return null|Entity
     */
    public function getEntity($type, $id = null)
    {
        if ($type == null){
            return null;
        }
        $type = 'Mautic\\WechatBundle\\Entity\\' . ucfirst($type);
        if ($id === null) {
            $entity = new $type;
        } else {
            $entity = $this->getRepository($type)->getEntity($id);
        }

        $this->factory->getLogger()->error('+++++++++getEntity name:' . $entity->_getName());

        return $entity;
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

        $isNew = $this->isNewEntity($entity);
        //set some defaults
        $this->setTimestamps($entity, $isNew, $unlock);

        $event = $this->dispatchEvent("pre_save", $entity, $isNew);
        $this->getRepository($entity->_getName())->saveEntity($entity);
        $this->dispatchEvent("post_save", $entity, $isNew, $event);
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

            $this->getRepository($entity->_getName())->saveEntity($entity, false);

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
        $this->getRepository($entity->_getName())->nullVariantParent($entity->getId());
        return $this->getRepository($entity->_getName())->deleteEntity($entity);
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
        $this->factory->getLogger()->error('----createForm entity entity:' . $entity->_getName());
        if ((!$entity instanceof Account) &&
            (!$entity instanceof Article) &&
            (!$entity instanceof Message) &&
            (!$entity instanceof News) &&
            (!$entity instanceof Stat)) {
            throw new MethodNotAllowedHttpException(array('Stat', 'Article','Message', 'News','Stat', ''));
        }

        if (!empty($action)) {
            $options['action'] = $action;
        }

        return $formFactory->create(strtolower($entity->_getName()), $entity, $options);
     }



    /**
     *
     * @param $type, $id
     *
     * @return null|arr
     */
    public function getWechatTypeData($type, $id)
    {
        if ($id === null and $type==null) {
            return null;
        }

        $data = null;
        if (strtolower($type) == 'news'){
            $entity = $this->getEntity('News', $id);
            if(!empty($entity)){
                $data = array(
                    'title'       => $entity->getTitle(),
                    'description' => $entity->getDescription(),
                    'url'         => $entity->getUrl(),
                    'image'       => $entity->getImage(),
                );
            }
        }

        return $data;
    }

    /**
     * Get a specific entity or generate a new one if id is empty
     *
     * @param $id
     *
     * @return null|arr
     */
    public function getSendMessage($id = null)
    {
        if ($id === null) {
            return null;
        }
        $message = $this->getEntity('Message', $id);
        if (empty($message)){
            return null;
        }
        $content = $message->getContent();
        if (empty($content)){
            return null;
        }
        $contentArr = json_decode($content);
        if (empty($contentArr)){
            return null;
        }

        $sendMessages = array();
        foreach ($contentArr as $key => $value) {
            $msg = $this->getWechatTypeData($value->type, $value->id);
            if (!empty($msg)){
                array_push($sendMessages, $msg);
            }
        }

        return $sendMessages;
    }


    public function processWechatEvent($stat, $request) {
        $event = new WechatEvent($stat, $request);

        $eventType = $event->getEventType();

        if ($eventType == 'account_followed') {
            $leadModel = $this->factory->getModel('lead');

            $openId = $stat->getOpenId();
            $originalId = $stat->getOriginalId();

            $openidRepo = $this->getRepository('Openid');
            $ary = $openidRepo->findByOpenId($openId);

            if (count($ary) == 0) {
                $lead = new Lead();
                $lead->setNewlyCreated(true);
                $lead->setLastActive(new \DateTime());
                $lead->addUpdatedField('email', $openId . '@weixin.com');
                $leadModel->saveEntity($lead, false);

                $accountRepo = $this->getRepository('Account');
                $account = $accountRepo->findByOriginalId($originalId);
                if (isset($account)) {
                    $leadId = $lead->getId();
                    $accountId = $account->getId();
                    $openidEntity = new Openid();
                    $openidEntity->setOpenId($openId);
                    $openidEntity->setLead($lead);
                    $openidEntity->setAccount($account);
                    $openidEntity->setFollowed(true);
                    $openidRepo->saveEntity($openidEntity);

                    $campaignModel = $this->factory->getModel('campaign');
                    $campaigns = $campaignModel->getRepository()->findByWechatAccountId($accountId);
                    if (!empty($campaigns)) {
                        foreach ($campaigns as $campaign) {
                            $campaignModel->addLead($campaign, $lead);
                        }
                    }
                }
            }
        } else if ($eventType == 'message_received') {

        } else if ($eventType == 'article_opened') {

        } else if ($eventType == 'article_shared') {

        } else {

        }

        $this->getRepository()->saveEntity($stat);
    }


}
