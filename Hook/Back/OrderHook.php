<?php
/*************************************************************************************/
/*      This file is part of the module AdminTools                           */
/*                                                                                   */
/*      For the full copyright and license information, please view the LICENSE.txt  */
/*      file that was distributed with this source code.                             */
/*************************************************************************************/

namespace AdminTools\Hook\Back;

use AdminTools\AdminTools;
use Thelia\Core\Event\Hook\HookRenderEvent;
use Thelia\Core\Hook\BaseHook;
use Thelia\Core\Thelia;

class OrderHook extends BaseHook
{
    public function onOrdersTableHeader(HookRenderEvent $event)
    {
        $event->add($this->render(
            'admin-order-creation/hook/orders.table-header.html',
            $event->getArguments()
        ));
    }

    public function onOrderJs(HookRenderEvent $event)
    {
        $event->add($this->render(
            'admin-order-creation/hook/orders.js.html',
            array_merge($event->getArguments() + [

            ])
        ));
    }
}
