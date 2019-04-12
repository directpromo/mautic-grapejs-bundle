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
use Mautic\CoreBundle\Event\CustomAssetsEvent;
use Mautic\CoreBundle\EventListener\CommonSubscriber;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\RouterInterface;

class AssetSubscriber extends CommonSubscriber
{


    public static function getSubscribedEvents()
    {
        return [
            CoreEvents::VIEW_INJECT_CUSTOM_ASSETS => ['injectAssets', -10],
        ];
    }

    /**
     * @param CustomAssetsEvent $assetsEvent
     */
    public function injectAssets(CustomAssetsEvent $assetsEvent)
    {
        $assetsEvent->addScript('plugins/MauticGrapeJsBundle/Assets/js/builder.js');
    }
}
