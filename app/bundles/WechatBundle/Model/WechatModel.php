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
use Mautic\WechatBundle\Entity\Article;
use Mautic\WechatBundle\Entity\Message;
use Mautic\WechatBundle\Entity\News;
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
    public function getRepository($name = null)
    {
        if (empty($name)){
            return $this->factory->getEntityManager()->getRepository('MauticWechatBundle:Account');
        }else{
            return $this->factory->getEntityManager()->getRepository('MauticWechatBundle:' . $name);
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
     * {@inheritdoc}
     *
     * @param Wechat $entity
     * @param       $unlock
     *
     * @return mixed
     */
    public function _saveEntity ($name, Account $entity, $unlock = true)
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
        $this->getRepository($name)->saveEntity($entity);
        $this->dispatchEvent("post_save", $entity, $isNew, $event);
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
        if ($entity instanceof Account){

            _saveEntity ('Account', $entity, $unlock);

        }elseif($entity instanceof Article){

            _saveEntity ('Article', $entity, $unlock);

        }elseif($entity instanceof Message){

            _saveEntity ('Message', $entity, $unlock);

        }elseif($entity instanceof News){

            _saveEntity ('News', $entity, $unlock);

        }elseif($entity instanceof Stat){

            _saveEntity ('Stat', $entity, $unlock);

        }else{
            return;
        }
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
        if ($entity instanceof Account){

            $this->getRepository('Account')->nullVariantParent($entity->getId());
            return $this->getRepository('Account')->deleteEntity($entity);

        }elseif($entity instanceof Article){

            return $this->getRepository('Article')->deleteEntity($entity);

        }elseif($entity instanceof Message){

            return $this->getRepository('Message')->deleteEntity($entity);

        }elseif($entity instanceof News){

            return $this->getRepository('News')->deleteEntity($entity);

        }elseif($entity instanceof Stat){

            return $this->getRepository('Stat')->deleteEntity($entity);

        }else{
            return;
        }
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
         return $formFactory->create($entity->getName, $entity, $options);
     }

    /**
     *
     * @param string $type, $id
     *
     * @return null|Entity
     */
    public function getEntity($type, $id = null)
    {
        if ($id === null) {
            return null;
        }

        return $this->getRepository($type)->getEntity($id);
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

        } else if ($eventType == 'message_received') {

        } else if ($eventType == 'article_opened') {

        } else if ($eventType == 'article_shared') {

        } else {

        }

        $this->getRepository()->saveEntity($stat);
    }


}
