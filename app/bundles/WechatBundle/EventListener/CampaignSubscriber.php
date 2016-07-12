<?php

namespace Mautic\WechatBundle\EventListener;

use Mautic\CoreBundle\EventListener\CommonSubscriber;
use Mautic\CampaignBundle\Event\CampaignBuilderEvent;
use Mautic\CampaignBundle\CampaignEvents;

/*
 * Class CampaignSubscriber
 *
 * @package MauticWechatBundle
 */
class CampaignSubscriber extends CommonSubscriber
{
    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return array(
            CampaignEvents::CAMPAIGN_ON_BUILD => array('onCampaignBuild', 0)
        );
    }

    public function onCampaignBuild(CampaignBuilderEvent $event)
    {
        if ($this->factory->getParameter('wechat_enabled')) {
            $action = array(
                'label' =>  'mautic.wechat.campaign.event.send_message',
                'description' => 'mautic.wechat.campaign.event.send_message_descr',
                'callback'         => null,
                #'formType'         => 'wechatsend_list',
                #'formTypeOptions'  => array('update_select' => 'campaignevent_properties_sms'),
                #'formTheme'        => 'MauticWechatBundle:FormTheme\WechatSendList',
                #'timelineTemplate' => 'MauticWechatBundle:SubscribedEvents\Timeline:index.html.php'
            );
            $event->addAction('wechat.send_message', $action);

            $trigger = array(
                'label'           => 'mautic.wechat.campaign.event.follow',
                'description'     => 'mautic.wechat.campaign.event.follow_descr'
            );
            $event->addLeadDecision('wechat.follow', $trigger);

            $trigger = array(
                'label'           => 'mautic.wechat.campaign.event.message_received',
                'description'     => 'mautic.wechat.campaign.event.message_received_descr'
            );
            $event->addLeadDecision('wechat.message_received', $trigger);

            $trigger = array(
                'label'       => 'mautic.wechat.campaign.event.article_opened',
                'description' => 'mautic.wechat.campaign.event.article_opened_descr',
            );
            $event->addLeadDecision('wechat.article_opened', $trigger);

            $trigger = array(
                'label'       => 'mautic.wechat.campaign.event.article_shared',
                'description' => 'mautic.wechat.campaign.event.article_shared_descr',
            );
            $event->addLeadDecision('wechat.article_shared', $trigger);
        }
    }
}
