<?php

namespace AdminTools\Controller;

use AdminTools\AdminTools;
use AdminTools\Event\RemoveUselessCartEvent;
use AdminTools\Event\RemoveUselessCartEvents;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Thelia\Controller\Admin\BaseAdminController;
use Thelia\Core\HttpFoundation\Session\Session;
use Thelia\Core\Security\AccessManager;
use Thelia\Core\Security\Resource\AdminResources;
use Thelia\Core\Translation\Translator;
use Thelia\Model\AdminLogQuery;

class AdminToolsController extends BaseAdminController
{
    /**
     * @return mixed|\Thelia\Core\HttpFoundation\Response
     */
    public function viewConfigAction()
    {
        if (null !== $response = $this->checkAuth(array(AdminResources::MODULE), AdminTools::DOMAIN_NAME, AccessManager::VIEW)) {
            return $response;
        }

        return $this->render("admin-tools-module-configuration",
            []
        );
    }



    /**
     * Remove carts with last_update older than the given date
     *
     * @return mixed|RedirectResponse|\Thelia\Core\HttpFoundation\Response
     */
    public function removeUselessCart(Session $session, EventDispatcherInterface $dispatcher)
    {
        if (null !== $response = $this->checkAuth(array(AdminResources::MODULE), AdminTools::DOMAIN_NAME, AccessManager::DELETE)) {
            return $response;
        }

        $form = $this->createForm('removeuselesscart_form');

        try {
            // Validate form
            $vForm = $this->validateForm($form, 'POST');

            // Build event from form data & dispatch it
            $event = new RemoveUselessCartEvent($vForm->getData()['start_date'], $vForm->getData()['remove_all']);
            $dispatcher->dispatch($event, RemoveUselessCartEvents::REMOVE_USELESS_CARTS);

            // Get number of removed carts
            $session->getFlashBag()->add(
                'remove-cart-result',
                Translator::getInstance()->trans(
                    'Successfully removed %nbCarts carts!',
                    ['%nbCarts' => $event->getRemovedCarts()],
                    AdminTools::DOMAIN_NAME
                )
            );

            // Redirect
            return new RedirectResponse($form->getSuccessUrl());
        } catch (\Exception $e) {
            $this->setupFormErrorContext(
                'configuration',
                $e->getMessage(),
                $form
            );

            return $this->render('admin-tools-module-configuration', []);
        }
    }

    public function clearAdminLogs (Session $session){
        if (null !== $response = $this->checkAuth(array(AdminResources::MODULE), AdminTools::DOMAIN_NAME, AccessManager::DELETE)) {
            return $response;
        }

        $form = $this->createForm('removelogadmin_form');
        try {
            $logs = AdminLogQuery::create()->deleteAll();
            $session->getFlashBag()->add(
                'remove-admin-logs',
                Translator::getInstance()->trans(
                    'Successfully removed %nbLogs admin log!',
                    ['%nbLogs' => $logs],
                    AdminTools::DOMAIN_NAME
                )
            );
            return $this->render('admin-tools-module-configuration', []);

        }catch (\Exception $e) {
            $this->setupFormErrorContext(
                'configuration',
                $e->getMessage(),
                $form
            );
            return $this->render('admin-tools-module-configuration', []);
        }
    }
}