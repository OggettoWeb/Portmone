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
 * @subpackage Block
 */
class Oggetto_Portmone_Block_Standard_Redirect extends Mage_Core_Block_Abstract
{

    protected function _toHtml()
    {
        $standard = Mage::getModel('portmone/standard');
        $config = Mage::getModel('portmone/config');
        $orderIncrementId = $standard->getCheckout()->getLastRealOrderId();
        $order = Mage::getModel('sales/order')->loadByIncrementId($orderIncrementId);

        $form = new Varien_Data_Form();

        $form->setAction($config->getSubmitUrl())
                ->setId('portmone_standard_checkout')
                ->setMethod('POST')
                ->setUseContainer(true);

        $form->addField('payee_id', 'hidden', array(
            'name' => 'payee_id',
            'value' => $config->getPayeeId()
                )
        );

        $form->addField('shop_order_number', 'hidden', array(
            'name' => 'shop_order_number',
            'value' => $orderIncrementId
                )
        );

        $form->addField('bill_amount', 'hidden', array(
            'name' => 'bill_amount',
            'value' => $order->getGrandTotal()
                )
        );

        $form->addField('description', 'hidden', array(
            'name' => 'description',
            'value' => $config->getDescription($orderIncrementId)
                )
        );

        $form->addField('success_url', 'hidden', array(
            'name' => 'success_url',
            'value' => $config->getSuccessUrl()
                )
        );

        $form->addField('failure_url', 'hidden', array(
            'name' => 'failure_url',
            'value' => $config->getFailureUrl()
                )
        );

        $form->addField('lang', 'hidden', array(
            'name' => 'lang',
            'value' => $config->getLanguage()
                )
        );

        $html = '<html><body>';
        $html.= $this->__('You will be redirected to the Portmone website in a few seconds.');
        $html.= $form->toHtml();
        $html.= '<script type="text/javascript">document.getElementById("portmone_standard_checkout").submit();</script>';
        $html.= '</body></html>';

        return $html;
    }

}
