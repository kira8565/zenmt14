<?php

return array(
    'services'    => array(
        'events'  => array(
            'mautic.wechat.configbundle.subscriber' => array(
                'class' => 'Mautic\WechatBundle\EventListener\ConfigSubscriber'
            ),
        ),
        'forms' => array(
            'mautic.form.type.wechatconfig'  => array(
                'class' => 'Mautic\WechatBundle\Form\Type\ConfigType',
                'alias' => 'wechatconfig'
            ),
        ),
    ),
    'parameters' => array(
        'wechat_enabled' => false
    )
);
