<?php

return array(
    'routes' => array(
        'main'   => array(
            'mautic_wechat_index'  => array(
                'path'       => '/wechats/{page}',
                'controller' => 'MauticWechatBundle:Message:index'
            ),
            'mautic_wechat_action' => array(
                'path'       => '/wechats/{objectAction}/{objectId}',
                'controller' => 'MauticWechatBundle:Message:execute'
            )
        ),
        'public' => array(
            'mautic_wechat_event'         => array(
                'path'       => '/wechat/event',
                'controller' => 'MauticWechatBundle:Public:eventAgent'
            )
        )
    ),
    'services'    => array(
        'events'  => array(
            'mautic.wechat.configbundle.subscriber' => array(
                'class' => 'Mautic\WechatBundle\EventListener\ConfigSubscriber'
            ),
            'mautic.sms.campaignbundle.subscriber' => array(
                'class' => 'Mautic\WechatBundle\EventListener\CampaignSubscriber'
            )
        ),
        'forms' => array(
            'mautic.form.type.wechatconfig'  => array(
                'class' => 'Mautic\WechatBundle\Form\Type\ConfigType',
                'alias' => 'wechatconfig'
            ),
            'mautic.form.type.message' => array(
                'class'     => 'Mautic\WechatBundle\Form\Type\MessageType',
                'arguments' => 'mautic.factory',
                'alias'     => 'message'
            ),
            'mautic.form.type.account_list'     => array(
                'class'     => 'Mautic\WechatBundle\Form\Type\AccountListType',
                'arguments' => 'mautic.factory',
                'alias'     => 'account_list'
            ),
            'mautic.form.type.accountfollow_list' => array(
                'class'     => 'Mautic\WechatBundle\Form\Type\AccountFollowType',
                'arguments' => 'mautic.factory',
                'alias'     => 'accountfollow_list'
            ),
            'mautic.form.type.stat' => array(
                'class'     => 'Mautic\WechatBundle\Form\Type\StatType',
                'arguments' => 'mautic.factory',
                'alias'     => 'stat'
            ),
        )
    ),
    'parameters' => array(
        'wechat_enabled' => false
    )
);
