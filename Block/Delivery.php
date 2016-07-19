<?php
namespace Fluid\DeliveryUpsell\Block;

use \Magento\Checkout\Model\Cart;
class Delivery extends \Magento\Framework\View\Element\Template
{
    protected $_cart;
    protected $_scopeConfig;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Checkout\Model\Cart $cart,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->_scopeConfig = $scopeConfig;
        $this->_cart = $cart;
    }

    public function _prepareLayout()
    {
        return parent::_prepareLayout();
    }

    public function getCartTotal()
    {
        $items     = $this->_cart->getItems();
        $cartTotal = 0;

        foreach ($items as $item) {
            $rowTotal  = $item->getRowTotal();
            $cartTotal += $rowTotal;
        }

        return $cartTotal;
    }

    public function getFreeDeliveryEnabled()
    {
        $active = $this->_scopeConfig->getValue('freedelivery/general/active', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        return $active;
    }

    public function getFreeDeliveryCutOff()
    {
        $total = $this->_scopeConfig->getValue('freedelivery/general/total', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        return $total;
    }

    public function getFreeDeliveryAllowed()
    {
        $freeDelivery = $this->getFreeDeliveryCutOff();
        $cartTotal    = $this->getCartTotal();
        if ($freeDelivery - $cartTotal <= 0) {
            return true;
        } else {
            return number_format($freeDelivery - $cartTotal, 2);
        }
    }
}