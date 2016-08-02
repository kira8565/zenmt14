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
use Mautic\WechatBundle\Entity;
use Mautic\WechatBundle\Entity\Article;
use Mautic\WechatBundle\Entity\News;
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


    function assemblyNews(News $entity){
        if (!$entity instanceof News){
            return null;
        }

        return new News([
                    'title'       => $entity->getTitle(),
                    'description' => $entity->getDescription(),
                    'url'         => $entity->getUrl(),
                    'image'       => $entity->getImage(),
                    ]);
    }

    function assemblyArticle(Article $entity){
        if (!$entity instanceof Article){
            return null;
        }

        return new Article([
                    'title'             => $entity->getTitle(),
                    'author'            => $entity->getAuthor(),
                    'content'           => $entity->getContent(),
                    'content'           => $entity->getContent(),
                    'thumb_media_id'    => $entity->getThumbMediaId(),
                    'digest'            => $entity->getDigest(),
                    'source_url'        => $entity->getSourceUrl(),
                    'show_cover'        => $entity->getShowCover,
                    ]);
    }

    function assemblyWechatData(array $data = null){
        if (empty($data){
            return null;
        }

        $sendMessages = array();
        foreach($data as $key => $value){
            $type = empty($value->_getName()) ? '' : strtolower($value->_getName());
            if ($type == 'news'){
                $news = assemblyNews($value);
                if (!empty($news)){
                    array_push($sendMessages,$news);
                }

            }else if($type == 'article'){
                $article = assemblyArticle($value);
                if (!empty($article)){
                    array_push($sendMessages,$article);
                }
            }else{
                //do nothing
            }

        }
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
        $sendMessages = $this->assemblyWechatData($data);
        if (empty($sendMessages)){
            return;
        }

        return $app->staff->message($sendMessages)->to($openId)->send();
    }
}
