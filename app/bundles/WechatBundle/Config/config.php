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
        ),
    ),
    'parameters' => array(
        'wechat_enabled' => false
    )
);
