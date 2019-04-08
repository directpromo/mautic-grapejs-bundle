<?php

/*
 * @copyright   2016 Mautic Contributors. All rights reserved
 * @author      Mautic
 *
 * @link        http://mautic.org
 *
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

namespace MauticPlugin\MauticGrapeJsBundle\Controller;

use Mautic\CoreBundle\Controller\CommonController;
use Mautic\CoreBundle\Helper\CoreParametersHelper;
use Mautic\CoreBundle\Helper\EmojiHelper;
use Mautic\CoreBundle\Helper\InputHelper;

class GrapeJsController extends CommonController
{
    /**
     * Builder.
     *
     * @param $objectId
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function builderAction($objectId)
    {
        /** @var \Mautic\EmailBundle\Model\EmailModel $model */
        $model = $this->getModel('email');

        //permission check
        if (strpos($objectId, 'new') !== false) {
            $isNew = true;
            if (!$this->get('mautic.security')->isGranted('email:emails:create')) {
                return $this->accessDenied();
            }
            $entity = $model->getEntity();
            $entity->setSessionId($objectId);
        } else {
            $isNew  = false;
            $entity = $model->getEntity($objectId);
            if ($entity == null
                || !$this->get('mautic.security')->hasEntityAccess(
                    'email:emails:viewown',
                    'email:emails:viewother',
                    $entity->getCreatedBy()
                )
            ) {
                return $this->accessDenied();
            }
        }

        $template = InputHelper::clean($this->request->query->get('template'));
        $slots    = $this->factory->getTheme($template)->getSlots('email');

        //merge any existing changes
        $newContent = $this->get('session')->get('mautic.emailbuilder.'.$objectId.'.content', []);
        $content    = $entity->getContent();

        if (is_array($newContent)) {
            $content = array_merge($content, $newContent);
            // Update the content for processSlots
            $entity->setContent($content);
        }

        // Replace short codes to emoji
        $content = EmojiHelper::toEmoji($content, 'short');

        $logicalName = $this->factory->getHelper('theme')->checkForTwigTemplate(':'.$template.':email.html.php');

        $templateWithBody =  $this->renderView(
            $logicalName,
            [
                'isNew'    => $isNew,
                'slots'    => $slots,
                'content'  => $content,
                'email'    => $entity,
                'template' => $template,
                'basePath' => $this->request->getBasePath(),
            ]
        );

        /** @var CoreParametersHelper $coreParametersHelpers */
        $coreParametersHelpers = $this->get('mautic.helper.core_parameters');

        preg_match("/<body[^>]*>(.*?)<\/body>/is", $templateWithBody, $matches);
        $body = $matches[1];
        $templateWithoutBody = str_replace($body, '||BODY||', $templateWithBody);
        $hiddenTemplate = '<textarea id="templateBuilder" style="display:none">'. $templateWithoutBody.'</textarea>';
        $templateWithoutBody = str_replace('||BODY||', '', $templateWithoutBody);
        $libraries = $this->renderView('MauticGrapeJsBundle:Builder:head.html.php', [
            'siteUrl'     => $coreParametersHelpers->getParameter('site_url'),
        ]);
        $templateForBuilder = str_replace('</head>', $libraries.'</head>', $templateWithoutBody);

        $builderCode = $this->renderView('MauticGrapeJsBundle:Builder:builder.html.php', []);
        $templateForBuilder = str_replace('</body>', $builderCode.$hiddenTemplate.'</body>', $templateForBuilder);


        return $this->render(
            'MauticGrapeJsBundle:Builder:body.html.php',
            [
                'templateForBuilder'     => $templateForBuilder,
                'passthroughVars' => [
                    'activeLink'    => '#mautic_email_index',
                    'mauticContent' => 'email',
                ],
            ]
        );

    }
}
