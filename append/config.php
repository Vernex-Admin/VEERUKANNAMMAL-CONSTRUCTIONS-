<?php
/**
 * Site-wide settings. Change these values here only — every page reads them.
 */

// WhatsApp number used by every WhatsApp button and link on the site.
// Country code + number, digits only. Currently 93440 34068 (India, +91).
if (!defined('VK_WHATSAPP_NUMBER')) {
    define('VK_WHATSAPP_NUMBER', '919344034068');
}

if (!function_exists('vk_whatsapp_url')) {
    /**
     * wa.me link that opens WhatsApp with a pre-filled message.
     * It never sends anything by itself — the visitor still has to press Send.
     */
    function vk_whatsapp_url(string $message = ''): string
    {
        $url = 'https://wa.me/' . VK_WHATSAPP_NUMBER;
        return $message === '' ? $url : $url . '?text=' . rawurlencode($message);
    }
}
