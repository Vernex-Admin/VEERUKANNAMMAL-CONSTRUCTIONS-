<?php
/**
 * Shared <head> assets for every page — include it first thing inside <head>:
 *
 *     <?php $pageCss = ['css/alura-project.css']; include __DIR__ . '/append/head.php'; ?>
 *
 * $pageCss is optional (extra stylesheets for that page only).
 * $pagePreloadImage is optional: the page's LCP image (preloaded with high priority). Vendor sheets that only
 * one page needs (Magnific lightbox, Flaticon icons) are listed there too, and are
 * placed before style.css so the theme's overrides still win.
 * Each page keeps its own <title>, description, canonical, Open Graph and schema.
 */
require_once __DIR__ . '/config.php';

$pageCss = isset($pageCss) && is_array($pageCss) ? $pageCss : [];
$vkOptionalVendorCss = ['css/magnific-popup.min.css', 'css/flaticon.min.css'];
$pageVendorCss = array_values(array_intersect($pageCss, $vkOptionalVendorCss));
$pageCss = array_values(array_diff($pageCss, $vkOptionalVendorCss));
$pagePreloadImage = isset($pagePreloadImage) && is_string($pagePreloadImage) ? $pagePreloadImage : '';
$vkAssetVersion = '20261110j'; // bump when CSS changes so browsers fetch the new files
$vkAsset = static fn(string $path): string => htmlspecialchars($path . (str_contains($path, '?') ? '&' : '?') . 'v=' . $vkAssetVersion, ENT_QUOTES, 'UTF-8');
?>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#074461">
    <meta name="format-detection" content="telephone=yes">

    <!-- Icons -->
    <link rel="icon" type="image/png" sizes="32x32" href="images/icons/favicon-32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="images/icons/favicon-16.png">
    <link rel="apple-touch-icon" sizes="180x180" href="images/icons/apple-touch-icon.png">
    <link rel="manifest" href="site.webmanifest?v=3">

    <!-- Connect early to the icon CDN -->
    <link rel="preconnect" href="https://cdnjs.cloudflare.com" crossorigin>

    <!-- Self-hosted fonts: preload the two used above the fold (Poppins 400/600, latin) -->
    <link rel="preload" href="fonts/vk/poppins-latin-400.woff2" as="font" type="font/woff2" crossorigin>
    <link rel="preload" href="fonts/vk/poppins-latin-600.woff2" as="font" type="font/woff2" crossorigin>
<?php if ($pagePreloadImage !== ''): ?>
    <!-- This page's largest above-the-fold image (LCP) -->
    <link rel="preload" as="image" href="<?= htmlspecialchars($pagePreloadImage, ENT_QUOTES, 'UTF-8'); ?>" fetchpriority="high">
<?php endif; ?>

    <!-- Styles (the brand skin must stay last) -->
    <link rel="stylesheet" href="<?= $vkAsset('css/bootstrap.min.css'); ?>">
    <!-- Font Awesome 6 only; v4-shims keeps the site's existing "fa fa-*" class names working -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/v4-shims.min.css" crossorigin="anonymous" referrerpolicy="no-referrer">
    <link rel="stylesheet" href="<?= $vkAsset('css/owl.carousel.min.css'); ?>">
<?php foreach ($pageVendorCss as $vkCss): ?>
    <link rel="stylesheet" href="<?= $vkAsset($vkCss); ?>">
<?php endforeach; ?>
    <link rel="stylesheet" href="<?= $vkAsset('css/style.css'); ?>">
    <link rel="stylesheet" href="<?= $vkAsset('css/mobile-responsive.css'); ?>">
    <link rel="stylesheet" href="<?= $vkAsset('css/site-enhancements.css'); ?>">
<?php foreach ($pageCss as $vkCss): ?>
    <link rel="stylesheet" href="<?= $vkAsset($vkCss); ?>">
<?php endforeach; ?>
    <link rel="stylesheet" class="skin" href="<?= $vkAsset('css/skin/skin-5.css'); ?>">

    <!-- Fonts: Poppins + Roboto 400/600, self-hosted (font-display: swap) -->
    <link rel="stylesheet" href="<?= $vkAsset('css/fonts.css'); ?>">

<?php include __DIR__ . '/../google-analytics.php'; ?>
