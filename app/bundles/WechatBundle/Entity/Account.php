<?php
namespace Mautic\WechatBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Mautic\CoreBundle\Doctrine\Mapping\ClassMetadataBuilder;
use Mautic\CoreBundle\Helper\EmojiHelper;

/**
 * Class Account
 *
 * @package Mautic\WechatBundle\Entity
 */
class Account
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $description;

    /**
     * @var string
     */
    private $original_id;

    /**
     * @var string
     */
    private $token;

    /**
     * @var string
     */
    private $app_id;

    /**
     * @var string
     */
    private $app_secret;

    /**
     * @var string
     */
    private $aes_key;

    /**
     * @param ORM\ClassMetadata $metadata
     */
    public static function loadMetadata (ORM\ClassMetadata $metadata)
    {
        $builder = new ClassMetadataBuilder($metadata);

        $builder->setTable('wechat_accounts')
            ->setCustomRepositoryClass('Mautic\WechatBundle\Entity\AccountRepository');

        $builder->addIdColumns();

        $builder->createField('original_id', 'string')
            ->build();

        $builder->createField('token', 'string')
            ->build();

        $builder->createField('app_id', 'string')
            ->nullable()
            ->build();

        $builder->createField('app_secret', 'string')
            ->nullable()
            ->build();

        $builder->createField('aes_key', 'string')
            ->nullable()
            ->build();
    }

    /**
     * @param $id
     *
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param $name
     *
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param $description
     *
     * @return $this
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }
}


