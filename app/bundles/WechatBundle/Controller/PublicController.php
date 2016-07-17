<?php

namespace Mautic\WechatBundle\Controller;

use Mautic\CoreBundle\Controller\FormController as CommonFormController;
use Mautic\CoreBundle\Helper\EmojiHelper;
use Mautic\WechatBundle\Entity;
use Mautic\WechatBundle\Model;
use Mautic\CoreBundle\Helper\TrackingPixelHelper;
use Symfony\Component\HttpFoundation\Response;

class PublicController extends CommonFormController
{
    /**
     * @param
     *
     * @return Response
     * @throws \Exception
     * @throws \Mautic\CoreBundle\Exception\FileNotFoundException
     */
    public function eventAgentAction()
    {
        $model = $this->factory->getModel('wechat.stat');
        $entity  = $model->getEntity();

        // $request = $this->request;
        // $openId = $request->get('openId');
        // $accountId = $request->get('accountId');
        // $type = $request->get('type');
        // $messageId = $request->get('messageId');

        $action = $this->generateUrl('mautic_wechat_event', array('objectAction' => 'eventAgent'));

        //create the form
        $form = $model->createForm($entity, $this->get('form.factory'), $action, array('csrf_protection' => false));

        if ($this->isFormValid($form)) {
            //form is valid so process the data
            $model->saveEntity($entity);
            return new Response('{"status":200, "message":"ok"}', 200, array('Content-Type' => 'application/json;charset=UTF-8'));
        }else{
            return new Response('{"status":4000, "message":"invalid param"}', 200, array('Content-Type' => 'application/json;charset=UTF-8'));
        }
    }
}
