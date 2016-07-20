<?php
/**
 * @package     Mautic
 * @copyright   2016 Mautic Contributors. All rights reserved.
 * @author      Mautic
 * @link        http://mautic.org
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

namespace Mautic\WechatBundle;

/**
 * Class WechatEvents
 * Events available for WechatBundle
 *
 * @package Mautic\WechatBundle
 */
final class WechatEvents
{
    /**
     * The mautic.wechat_on_send event is thrown when a wechat is sent
     *
     * The event listener receives a
     * Mautic\WechatBundle\Event\WechatSendEvent instance.
     *
     * @var string
     */
    const WECHAT_ON_SEND = 'mautic.wechat_on_send';

    /**
     * The mautic.wechat_pre_save event is thrown right before a wechat is persisted.
     *
     * The event listener receives a
     * Mautic\WechatBundle\Event\WechatEvent instance.
     *
     * @var string
     */
    const WECHAT_PRE_SAVE = 'mautic.wechat_pre_save';

    /**
     * The mautic.wechat_post_save event is thrown right after a wechat is persisted.
     *
     * The event listener receives a
     * Mautic\WechatBundle\Event\WechatEvent instance.
     *
     * @var string
     */
    const WECHAT_POST_SAVE = 'mautic.wechat_post_save';

    /**
     * The mautic.wechat_pre_delete event is thrown prior to when a wechat is deleted.
     *
     * The event listener receives a
     * Mautic\WechatBundle\Event\WechatEvent instance.
     *
     * @var string
     */
    const WECHAT_PRE_DELETE = 'mautic.wechat_pre_delete';

    /**
     * The mautic.wechat_post_delete event is thrown after a wechat is deleted.
     *
     * The event listener receives a
     * Mautic\WechatBundle\Event\WechatEvent instance.
     *
     * @var string
     */
    const WECHAT_POST_DELETE = 'mautic.wechat_post_delete';
}