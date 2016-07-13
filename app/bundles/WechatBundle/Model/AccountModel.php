<?php
namespace Mautic\WechatBundle\Model;

use Mautic\CoreBundle\Helper\DateTimeHelper;
use Mautic\CoreBundle\Helper\GraphHelper;
use Mautic\CoreBundle\Model\FormModel;
use Mautic\CoreBundle\Helper\Chart\LineChart;
use Mautic\CoreBundle\Helper\Chart\PieChart;
use Mautic\CoreBundle\Helper\Chart\ChartQuery;
use Mautic\WechatBundle\Swiftmailer\Exception\BatchQueueMaxException;
use Mautic\WechatBundle\Entity\Account;
use Mautic\WechatBundle\Event\WechatBuilderEvent;
use Mautic\WechatBundle\Event\WechatEvent;
use Mautic\WechatBundle\Event\WechatOpenEvent;
use Mautic\WechatBundle\WechatEvents;
use Mautic\LeadBundle\Entity\DoNotContact;
use Mautic\LeadBundle\Entity\Lead;
use Doctrine\DBAL\Query\QueryBuilder;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

/**
 * Class AccountModel
 * {@inheritdoc}
 * @package Mautic\CoreBundle\Model\FormModel
 */
class AccountModel extends FormModel
{
    /**
     * {@inheritdoc}
     *
     * @return \Mautic\WechatBundle\Entity\AccountRepository
     */
    public function getRepository()
    {
        return $this->em->getRepository('MauticWechatBundle:Account');
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
         if (!$entity instanceof Account) {
             throw new MethodNotAllowedHttpException(array('Account'));
         }
         if (!empty($action)) {
             $options['action'] = $action;
         }
         return $formFactory->create('account', $entity, $options);
     }

    /**
     * Get a specific entity or generate a new one if id is empty
     *
     * @param $id
     *
     * @return null|Wechat Account
     */
    public function getEntity ($id = null)
    {
        if ($id === null) {
            $entity = new Account;
        } else {
            $entity = parent::getEntity($id);
        }

        return $entity;
    }

}
