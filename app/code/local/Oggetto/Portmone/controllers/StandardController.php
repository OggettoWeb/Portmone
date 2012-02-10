<?php

/**
 * Oggetto extension for Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade
 * the Oggetto Portmone module to newer versions in the future.
 * If you wish to customize the Oggetto Portmone module for your needs
 * please refer to http://www.magentocommerce.com for more information.
 *
 * @category   Oggetto
 * @package    Oggetto_Portmone
 * @copyright  Copyright (C) 2011 Oggetto Web ltd (http://oggettoweb.com/)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 *
 * @category   Oggetto
 * @package    Oggetto_Portmone
 * @subpackage Controller
 */
class Oggetto_Portmone_StandardController extends Mage_Core_Controller_Front_Action
{

    public function redirectAction()
    { 
        $session = Mage::getSingleton('checkout/session');
        $session->setPortmoneStandardQuoteId($session->getQuoteId());
        $this->getResponse()->setBody($this->getLayout()->createBlock('portmone/standard_redirect')->toHtml());
//        $session->unsQuoteId();
//        $session->unsRedirectUrl();
    }

    public function getOnepage()
    {
        return Mage::getSingleton('checkout/type_onepage');
    }

    /**
     * when paypal returns
     * The order information at this point is in POST
     * variables.  However, you don't want to "process" the order until you
     * get validation from the IPN.
     */
    public function successAction()
    {
        $orderId = $this->getRequest()->getParam('SHOPORDERNUMBER');
        if ($orderId) {
            $order = Mage::getModel('sales/order')->loadByIncrementId($orderId);
            try {
                if (!$order->canInvoice()) {
                    Mage::throwException(Mage::helper('core')->__('Can not create an invoice.'));
                }

                $invoice = Mage::getModel('sales/service_order', $order)->prepareInvoice();

                if (!$invoice->getTotalQty()) {
                    Mage::throwException(Mage::helper('core')->__('Cannot create an invoice without products.'));
                }

                $invoice->setRequestedCaptureCase(Mage_Sales_Model_Order_Invoice::CAPTURE_ONLINE);
                $invoice->register();
                $transactionSave = Mage::getModel('core/resource_transaction')
                                ->addObject($invoice)
                                ->addObject($invoice->getOrder());

                $transactionSave->save();
            } catch (Mage_Core_Exception $e) {

            }
        }
        $session = Mage::getSingleton('checkout/session');
        $session->setQuoteId($session->getPortmoneStandardQuoteId(true));
        Mage::getSingleton('checkout/session')->getQuote()->setIsActive(false)->save();
        $this->_redirect('checkout/onepage/success', array('_secure' => true));
    }

    public function failureAction()
    {
        $orderId = $this->getRequest()->getParam('SHOPORDERNUMBER');
        if ($orderId) {
            $order = Mage::getModel('sales/order')->loadByIncrementId($orderId);
            try {
                if (!$order->canCancel()) {
                    Mage::throwException(Mage::helper('core')->__('Can not cancel.'));
                }
                $order->cancel();
                $order->save();
            } catch (Mage_Core_Exception $e) {

            }
        }
//        $session = Mage::getSingleton('checkout/session');
//        $session->setQuoteId($session->getPortmoneStandardQuoteId(true));
//        Mage::getSingleton('checkout/session')->getQuote()->setIsActive(false)->save();
        $this->_redirect('checkout/onepage/failure', array('_secure' => true));
    }

}
