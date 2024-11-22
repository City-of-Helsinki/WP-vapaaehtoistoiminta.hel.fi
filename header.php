<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package nuhe
 */

use Geniem\Theme\Model\Header;

/**
 * View model
 *
 * @var $model Header
 */
$model          = ModelController()->get_class( 'Header' );
$html_classes   = $model->html_classes();
$head_scripts   = $model->get_head_scripts();
$cookiebot_lang = $model->cookiebot_lang();
$blog_url       = get_home_url();
?>
<!doctype html>
<html <?php language_attributes(); ?> class="<?php echo esc_attr( $html_classes ); ?>">
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="https://gmpg.org/xfn/11">

    <?php if ( ! empty( $cookiebot_lang ) ) : ?> 
        <script id="Cookiebot" src="https://consent.cookiebot.com/uc.js" data-culture="<?php echo esc_attr( $cookiebot_lang ); ?>" data-cbid="585b0cb7-a01f-4d63-a189-5cad5c762a77" type="text/javascript" async></script>
    <?php endif; ?>

    <?php wp_head(); ?>

    <?php if ( ! empty( $head_scripts ) ) : ?>
        <?php echo wp_kses_post( $head_scripts ); ?>
    <?php endif; ?>
</head>

<body <?php body_class( 'text-body text-md' ); ?>>
<?php wp_body_open(); ?>

<div class="page-wrapper">

<?php get_template_part( 'partials/header', '', [
    'logo'               => $model->logo(),
    'service_name'       => $model->service_name(),
    'search_results_url' => $model->search_results_url(),
    'languages'          => $model->languages(),
    'facebook_link'      => VET()->get_facebook_link(),
] ); ?>
