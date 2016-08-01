<?php

namespace Mautic\LeadBundle\Form\Type;

use Mautic\CoreBundle\Factory\MauticFactory;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

/**
 * Class CampaignEventLeadTagValueType
 */
class CampaignEventLeadTagValueType extends AbstractType
{
    private $factory;

    public function __construct(MauticFactory $factory)
    {
        $this->factory = $factory;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $choices     = array();

        $repo = $this->factory->getEntityManager()->getRepository('MauticLeadBundle:Tag');
        $tags  = $repo->getTagList('', 0, 0);
        foreach ($tags as $tag) {
            $choices[$tag['id']] = $tag['tag'];
        }

        $builder->add('field_tag', 'choice', array(
            'label'         => 'mautic.lead.campaign.event.tag',
            'choices'       => $choices,
            'label_attr'    => array('class' => 'control-label'),
            'multiple'      => false,
            'empty_value'   => 'mautic.core.select',
            'attr'          => array(
                'class'     => 'form-control',
                'tooltip'   => 'mautic.lead.campaign.event.tag_descr'
            )
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return "campaignevent_lead_tag_value";
    }
}
