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
     */
    public function __construct(IntegrationHelper $helper)
    {
        $this->helper = $helper;
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
        $windowUrlEdit = '';
        if (0 === strpos($event->getRoute(), 'mautic_email_')) {
            echo $event->getItem();
            if ($event->getItem()) {
                $lookupContactButton = [
                    'attr' => [
                        'class'    => 'btn btn-primary btn-nospin',
                        'onclick'  => 'Mautic.loadNewWindow(Mautic.standardFocusUrl({"windowUrl": "'.$windowUrlEdit.'"}))',
                        'icon'     => 'fa fa-edit',
                    ],
                    'btnText'   => 'mautic.focus.show.edit.item',
                ];
//page.header.right
                die(print_r('a'));
                $event
                    ->addButton(
                        $lookupContactButton,
                        ButtonHelper::LOCATION_TOOLBAR_ACTIONS,
                        ['mautic_email_action', ['objectAction' => 'edit']]
                    );
            }
        }
    }
}
