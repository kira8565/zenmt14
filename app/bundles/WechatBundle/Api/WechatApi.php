<?php
/**
 * @package     Mautic
 * @copyright   2016 Mautic Contributors. All rights reserved.
 * @author      Mautic
 * @link        http://mautic.org
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */
namespace Mautic\WechatBundle\Api;

use Joomla\Http\Response;
use Mautic\CoreBundle\Factory\MauticFactory;
use Mautic\LeadBundle\Entity\Lead;
use Mautic\WechatBundle\Entity\Account;
use EasyWeChat\Foundation\Application;
use EasyWeChat\Message\News;

class WechatApi extends AbstractWechatApi
{
    /**
     * @var string
     */
    protected $appId;

    /**
     * @var string
     */
    protected $secret;

    /**
     * @var string
     */
    protected $token;

    /**
     * @var string
     */
    protected $aesKey;

    /**
     * @var EasyWeChat\Foundation\Application
     */
    protected $app;

    /**
     * @param MauticFactory $factory
     * @param \Services_Twilio $client
     * @param string $sendingPhoneNumber
     */
    public function __construct(MauticFactory $factory)
    {
        parent::__construct($factory);
    }


    /**
     * @param Mautic\WechatBundle\Entity Account
     *
     * @return EasyWeChat\Foundation\Application
     */
    protected function getWechatApp(Account $account)
    {
        $options = [
            'debug'  => true,

            'log'    => [
                'level' => 'debug',
                'file'  => '/home/www/easywechat.log',
            ],
            'app_id'  => $account->getAppId(),         // AppID
            'secret'  => $account->getAppSecret(),     // AppSecret
            'token'   => $account->getToken(),         // Token
            'aes_key' => $account->getAesKey(),        // EncodingAESKey

        ];

        return new Application($options);
    }

    /**
     * @param Mautic\WechatBundle\Entity Account
     * @param string $number
     * @param string $content
     *
     * @return array
     */
    public function sendWechat(Account $account, $openId='', $data=[])
    {

        $app = $this->getWechatApp($account);
        $sendMessages = array();
        foreach($data as $key => $value){
            array_push($sendMessages, new News($value));
        }
        return $app->staff->message($sendMessages)->to($openId)->send();
    }
}
