<?php

/**
 * 2017 HiPay
 *
 * NOTICE OF LICENSE
 *
 * @author    HiPay <support.wallet@hipay.com>
 * @copyright 2017 HiPay
 * @license   https://github.com/hipay/hipay-wallet-sdk-prestashop/blob/master/LICENSE.md
 */
class HipayHelper
{

    /**
     *
     * empty customer cart
     * @return boolean
     */
    public static function unsetCart()
    {
        $context                    = Context::getContext();
        $cart                       = new Cart($context->cookie->id_cart);
        unset($context->cookie->id_cart,
            $cart,
            $context->cookie->checkedTOS);
        $context->cookie->check_cgv = false;
        $context->cookie->write();
        $context->cookie->update();
        return true;
    }

    /**
     * Check if hipay server signature match post data + passphrase
     * @param type $signature
     * @param type $config
     * @param type $fromNotification
     * @return boolean
     */
    public static function checkSignature(
    $signature, $config, $fromNotification = false, $moto = false
    )
    {
        $passphrase     = ($config["account"]["global"]["sandbox_mode"]) ? $config["account"]["sandbox"]["api_secret_passphrase_sandbox"]
                : $config["account"]["production"]["api_secret_passphrase_production"];
        $passphraseMoto = ($config["account"]["global"]["sandbox_mode"]) ? $config["account"]["sandbox"]["api_secret_passphrase_sandbox"]
                : $config["account"]["production"]["api_secret_passphrase_production"];

        if (empty($passphrase) && empty($signature)) {
            return true;
        }

        if ($fromNotification) {
            $rawPostData = Tools::file_get_contents("php://input");
            if ($signature == sha1($rawPostData.$passphrase) || ($moto && $signature == sha1($rawPostData.$passphraseMoto))) {
                return true;
            }
            return false;
        }

        return false;
    }

    /**
     * Generate Product reference for basket
     * @param type $product
     * @return string
     */
    public static function getProductRef($product)
    {
        if (!empty($product["reference"])) {
            if (isset($product["attributes_small"])) {
                // Product with declinaison
                $reference = $product["reference"] . "-" . HipayHelper::slugify($product["attributes_small"]);
            } else {
                // Product simple or virtual
                $reference = $product["reference"];
            }
        } else {
            $reference = $product["id_product"]."-".$product["id_product_attribute"]."-".HipayHelper::slugify($product["name"]);
            if (isset($product["attributes_small"])) {
                $reference .=  "-".  HipayHelper::slugify($product["attributes_small"]);
            }
        }
        return $reference;
    }

    /**
     * Generate carrier product reference for basket
     * @param type $carrier
     * @return string
     */
    public static function getCarrierRef($carrier)
    {
        $reference = $carrier->id."-".HipayHelper::slugify($carrier->name);

        return $reference;
    }

    /**
     * Generate discount product reference for basket
     * @param type $discount
     * @return string
     */
    public static function getDiscountRef($discount)
    {
        if (!empty($discount["code"])) {
            $reference = $discount["code"];
        } else {
            $reference = $discount["id_cart_rule"]."-".HipayHelper::slugify($discount["name"]);
        }

        return $reference;
    }

    /**
     * Slugify text
     * @param type $text
     * @return string
     */
    public static function slugify($text)
    {
        // replace non letter or digits by -
        $text = preg_replace('#[^\\pL\d]+#u',
            '-',
            $text);

        // trim
        $text = trim($text,
            '-');

        // transliterate
        if (function_exists('iconv')) {
            $text = iconv('utf-8',
                'us-ascii//TRANSLIT',
                $text);
        }

        // lowercase
        $text = Tools::strtolower($text);

        // remove unwanted characters
        $text = preg_replace('#[^-\w]+#',
            '',
            $text);

        if (empty($text)) {
            return 'n-a';
        }

        return $text;
    }

    /**
     *
     * @return string
     */
    public static function getAdminUrl()
    {
        if (_PS_VERSION_ < '1.7' && _PS_VERSION_ >= '1.6') {
            $admin       = explode(DIRECTORY_SEPARATOR,
                _PS_ADMIN_DIR_);
            $adminFolder = array_pop((array_slice($admin,
                    -1)));
            $adminUrl    = _PS_BASE_URL_.__PS_BASE_URI__.$adminFolder.'/';
        } else {
            $adminUrl = '';
        }
        return $adminUrl;
    }

    /**
     * Generate unique token
     * @param type $cartId
     * @param type $page
     * @return type
     */
    public static function getHipayToken($cartId, $page = 'validation.php')
    {
        return md5(Tools::getToken($page).$cartId);
    }

    /**
     * Generate unique admin token
     * @param type $cartId
     * @param type $page
     * @return type
     */
    public static function getHipayAdminToken($tab, $orderID)
    {
        return md5(Tools::getAdminTokenLite($tab).$orderID);
    }
}