<?php

namespace Mautic\WechatBundle\Helper;

use Mautic\LeadBundle\Entity\DoNotContact;
use Mautic\LeadBundle\Entity\Lead;
use Mautic\CoreBundle\Factory\MauticFactory;

class WechatHelper
{
    /**
     * @var MauticFactory
     */
    protected $factory;

    /**
     * @param MauticFactory $factory
     */
    public function __construct(MauticFactory $factory)
    {
        $this->factory = $factory;
    }

    /**
     * @param array $config
     * @param Lead $lead
     * @param MauticFactory $factory
     *
     * @return boolean
     */
    public static function send(array $config, Lead $lead, MauticFactory $factory)
    {
        $leadId = $lead->getId();
        $leadEmail = $lead->getEmail();
        $configStr = print_r($config, true);
        error_log("WechatHelper:send, leadId:$leadId, leadEmail:$leadEmail, config:$configStr\n", 3, '/tmp/mautic.log');
        return array();
    }
}
