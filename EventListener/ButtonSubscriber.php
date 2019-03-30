<?php

/*
 * @copyright   2016 Mautic Contributors. All rights reserved
 * @author      Mautic, Inc.
 *
 * @link        https://mautic.org
 *
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

namespace MauticPlugin\MauticGrapeJsBundle\EventListener;

use Mautic\CoreBundle\CoreEvents;
use Mautic\CoreBundle\Event\CustomButtonEvent;
use Mautic\CoreBundle\EventListener\CommonSubscriber;
use Mautic\CoreBundle\Templating\Helper\ButtonHelper;
use Mautic\PluginBundle\Helper\IntegrationHelper;
use Symfony\Component\Routing\RouterInterface;

class ButtonSubscriber extends CommonSubscriber
{
    /**
     * @var IntegrationHelper
     */
    private $helper;

    /**
     * ButtonSubscriber constructor.
     *
     * @param IntegrationHelper $helper
     * @param RouterInterface   $router
     */
    public function __construct(IntegrationHelper $helper, RouterInterface $router)
    {
        $this->helper = $helper;
        $this->router = $router;
    }

    public static function getSubscribedEvents()
    {
        return [
            CoreEvents::VIEW_INJECT_CUSTOM_BUTTONS => ['injectViewButtons', 0],
        ];
    }

    /**
     * @param CustomButtonEvent $event
     */
    public function injectViewButtons(CustomButtonEvent $event)
    {
        $myIntegration = $this->helper->getIntegrationObject('GrapeJs');

        if (false === $myIntegration || !$myIntegration->getIntegrationSettings()->getIsPublished()) {
            return;
        }
        if (0 === strpos($event->getRoute(), 'mautic_email_')) {
            if ($event->getItem()) {
                /** @var RouterInterface $route */
                $builder = $this->router->generate(
                    'mautic_dynamicContent_action',
                    [
                        'objectAction' => 'edit',
                        'objectId'     => 'dynamicContentId',
                        'contentOnly'  => 1,
                    ]
                );

                $builder = 'http://mautic.test/mautic/grapejs.html';

                $lookupContactButton = [
                    'attr' => [
                        'class'    => 'btn btn-primary btn-nospin',
                        'onclick' => 'Mautic.loadNewWindow({windowUrl: \''.$builder.'\'})',
                        'icon'     => 'fa fa-edit',
                    ],
                    'btnText'   => 'nieco',
                ];
                $event
                    ->addButton(
                        $lookupContactButton,
                        ButtonHelper::LOCATION_PAGE_ACTIONS,
                        ['mautic_email_action', ['objectAction' => 'view']]
                    );
            }
        }
    }
}
