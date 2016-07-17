<?php
/**
 * @package     Mautic
 * @copyright   2014 Mautic Contributors. All rights reserved.
 * @author      Mautic
 * @link        http://mautic.org
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

namespace Mautic\WechatBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Mautic\CoreBundle\Entity\FormEntity;
use Mautic\CoreBundle\Doctrine\Mapping\ClassMetadataBuilder;
use Mautic\CoreBundle\Entity\IpAddress;
use Mautic\CoreBundle\Helper\EmojiHelper;
use Mautic\LeadBundle\Entity\Lead;
use Mautic\ApiBundle\Serializer\Driver\ApiMetadataDriver;
/**
 * Class Stat
 *
 * @package Mautic\WechatBundle\Entity
 */
class Stat extends FormEntity
{

    /**
     * @var int
     */
    private $id;

    /**
     * @var Wechat
     */
    private $account;

    /**
     * @var \Mautic\LeadBundle\Entity\Lead
     */
    private $lead;

    /**
     * @var \Mautic\LeadBundle\Entity\LeadList
     */
    private $list;

    /**
     * @var \Mautic\CoreBundle\Entity\IpAddress
     */
    private $ipAddress;

    /**
     * @var \DateTime
     */
    private $dateSent;

    /**
     * @var \DateTime
     */
    private $dateRead;

    /**
     * @var string
     */
    private $type;

    /**
     * @var string
     */
    private $content;

    /**
     * @var string
     */
    private $trackingHash;

    /**
     * @var string
     */
    private $source;

    /**
     * @var int
     */
    private $sourceId;

    /**
     * @var array
     */
    private $tokens = array();

    /**
     * @param ORM\ClassMetadata $metadata
     */
    public static function loadMetadata (ORM\ClassMetadata $metadata)
    {
        $builder = new ClassMetadataBuilder($metadata);

        $builder->setTable('wechat_stats')
            ->setCustomRepositoryClass('Mautic\WechatBundle\Entity\StatRepository')
            ->addIndex(array('account_id', 'lead_id'), 'stat_wechat_search')
            ->addIndex(array('type'), 'stat_wechat_type_search')
            ->addIndex(array('tracking_hash'), 'stat_wechat_hash_search')
            ->addIndex(array('source', 'source_id'), 'stat_wechat_source_search');

        $builder->addId();

        $builder->createManyToOne('account', 'Account')
            ->inversedBy('stats')
            ->addJoinColumn('account_id', 'id', true, false, 'SET NULL')
            ->build();

        $builder->addLead(true, 'SET NULL');

        $builder->createManyToOne('list', 'Mautic\LeadBundle\Entity\LeadList')
            ->addJoinColumn('list_id', 'id', true, false, 'SET NULL')
            ->build();

        $builder->addIpAddress(true);

        $builder->createField('dateSent', 'datetime')
            ->columnName('date_sent')
            ->nullable()
            ->build();

        $builder->createField('dateRead', 'datetime')
            ->columnName('date_read')
            ->nullable()
            ->build();

        $builder->createField('type', 'string')
            ->columnName('type')
            ->build();

        $builder->createField('content', 'string')
            ->columnName('content')
            ->nullable()
            ->build();

        $builder->createField('trackingHash', 'string')
            ->columnName('tracking_hash')
            ->nullable()
            ->build();

        $builder->createField('source', 'string')
            ->nullable()
            ->build();

        $builder->createField('sourceId', 'integer')
            ->columnName('source_id')
            ->nullable()
            ->build();

        $builder->createField('tokens', 'array')
            ->nullable()
            ->build();
    }

    /**
     * Prepares the metadata for API usage
     *
     * @param $metadata
     */
    public static function loadApiMetadata(ApiMetadataDriver $metadata)
    {
        $metadata->setGroupPrefix('stat')
            ->addProperties(
                array(
                    'id',
                    'ipAddress',
                    'dateSent',
                    'dateRead',
                    'source',
                    'sourceId',
                    'trackingHash',
                    'lead',
                    'accoutn',
                    'type',
                    'content'
                )
            )
            ->build();
    }

    /**
     * @return mixed
     */
    public function getType ()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     */
    public function setType ($type)
    {
        $this->type = $type;
    }

    /**
     * @return mixed
     */
    public function getContent ()
    {
        return $this->content;
    }

    /**
     * @param mixed $dateSent
     */
    public function setContent ($content)
    {
        $this->content = $content;
    }

    /**
     * @return mixed
     */
    public function getDateSent ()
    {
        return $this->dateSent;
    }

    /**
     * @param mixed $dateSent
     */
    public function setDateSent ($dateSent)
    {
        $this->dateSent = $dateSent;
    }

    /**
     * @return mixed
     */
    public function getDateRead ()
    {
        return $this->dateRead;
    }

    /**
     * @param mixed $dateSent
     */
    public function setDateRead ($dateRead)
    {
        $this->dateRead = $dateRead;
    }

    /**
     * @return account
     */
    public function getAccount ()
    {
        return $this->account;
    }

    /**
     * @param mixed $account
     */
    public function setAccount (Account $account = null)
    {
        $this->account = $account;
    }

    /**
     * @return mixed
     */
    public function getId ()
    {
        return $this->id;
    }

    /**
     * @return IpAddress
     */
    public function getIpAddress ()
    {
        return $this->ipAddress;
    }

    /**
     * @param mixed $ip
     */
    public function setIpAddress (IpAddress $ip)
    {
        $this->ipAddress = $ip;
    }

    /**
     * @return Lead
     */
    public function getLead ()
    {
        return $this->lead;
    }

    /**
     * @param mixed $lead
     */
    public function setLead (Lead $lead = null)
    {
        $this->lead = $lead;
    }

    /**
     * @return mixed
     */
    public function getTrackingHash ()
    {
        return $this->trackingHash;
    }

    /**
     * @param mixed $trackingHash
     */
    public function setTrackingHash ($trackingHash)
    {
        $this->trackingHash = $trackingHash;
    }

    /**
     * @return \Mautic\LeadBundle\Entity\LeadList
     */
    public function getList ()
    {
        return $this->list;
    }

    /**
     * @param mixed $list
     */
    public function setList ($list)
    {
        $this->list = $list;
    }

    /**
     * @return mixed
     */
    public function getSource ()
    {
        return $this->source;
    }

    /**
     * @param mixed $source
     */
    public function setSource ($source)
    {
        $this->source = $source;
    }

    /**
     * @return mixed
     */
    public function getSourceId ()
    {
        return $this->sourceId;
    }

    /**
     * @param mixed $sourceId
     */
    public function setSourceId ($sourceId)
    {
        $this->sourceId = (int)$sourceId;
    }

    /**
     * @return mixed
     */
    public function getTokens ()
    {
        return $this->tokens;
    }

    /**
     * @param mixed $tokens
     */
    public function setTokens ($tokens)
    {
        $this->tokens = $tokens;
    }

}
