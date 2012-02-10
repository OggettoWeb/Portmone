<?php

/**
 * Oggetto Web extension for Magento
 *
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
 * @subpackage Model
 */
class Oggetto_Portmone_Model_Config extends Mage_Payment_Model_Config
{
    const PORTMONE_PAYMENT_PATH = 'payment/portmone/';
    const ORDER_ID_VARIABLE = '{{order_id}}';

    public function getSuccessUrl()
    {
        return Mage::getBaseUrl() . 'portmone/standard/success';
    }

    public function getFailureUrl()
    {
        return Mage::getBaseUrl() . 'portmone/standard/failure';
    }

    /**
     * Get data from config area
     *
     * @param string $path short path to config value
     * @param <type> $store store section of config
     *
     * @return string | boolean value of config data or false in case of absense
     */
    public function getConfigData($path)
    {
        if (!empty($path)) {
            return Mage::getStoreConfig(self::PORTMONE_PAYMENT_PATH . $path);
        }
        return false;
    }

    /**
     * Get payee_id
     *
     * @param int $storeId
     * @return string
     */
    public function getPayeeId()
    {
        return $this->getConfigData('payee_id');
    }

    public function getSubmitUrl()
    {
        return $this->getConfigData('submit_url');
    }

    public function getLanguage()
    {
        return $this->getConfigData('language');
    }

    public function getDescription($orderId)
    {
        $desc = $this->getConfigData('description');
        $desc = str_replace(self::ORDER_ID_VARIABLE, $orderId, $desc);
        return $desc;
    }

}
