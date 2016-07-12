<?php
/**
 * @package     Mautic
 * @copyright   2014 Mautic Contributors. All rights reserved.
 * @author      Mautic
 * @link        http://mautic.org
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8" />
    <title>ZenMT</title>
    <meta name="robots" content="noindex, nofollow" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <link rel="icon" type="image/x-icon" href="<?php echo $view['assets']->getUrl('media/images/favicon.ico') ?>" />
    <link rel="apple-touch-icon" href="<?php echo $view['assets']->getUrl('media/images/apple-touch-icon.png') ?>" />

    <?php $view['assets']->outputSystemStylesheets(); ?>
</head>
<body>
<section id="main" role="main">
    <div class="container" style="margin-top:100px;">
        <div class="row">
            <div class="col-lg-4 col-lg-offset-4">
                <div class="panel" name="form-login">
                    <div class="panel-body">
                        <div class="mautic-logo img-circle mb-md text-center">
                          <svg xmlns="http://www.w3.org/2000/svg" class="mautic-logo-figure" viewBox="0 0 29.4 29.4"><path d="M14.7,0A14.7,14.7,0,1,0,29.4,14.7,14.7,14.7,0,0,0,14.7,0ZM27.4,14.7a12.6,12.6,0,0,1-1.2,5.5H15.1l8-15A12.7,12.7,0,0,1,27.4,14.7ZM2,14.7A12.6,12.6,0,0,1,3.2,9.2H14.3l-8,15A12.7,12.7,0,0,1,2,14.7ZM14.7,27.4a12.6,12.6,0,0,1-6.8-2L17.6,7.2H4.4A12.7,12.7,0,0,1,21.5,4L11.8,22.2H25A12.7,12.7,0,0,1,14.7,27.4Z" style="fill:#ec6c00"/></svg>
                        </div>
                        <div id="main-panel-flash-msgs">
                            <?php echo $view->render('MauticCoreBundle:Notification:flashes.html.php'); ?>
                        </div>
                        <?php $view['slots']->output('_content'); ?>
                    </div>
                </div>
            </div>
        </div>
         <div class="row">
            <div class="col-lg-4 col-lg-offset-4 text-center text-muted">
                <?php echo $view['translator']->trans('mautic.core.copyright', array('%date%' => date('Y'))); ?>
            </div>
        </div>
    </div>
</section>
<script>
    //clear typeahead caches
    window.localStorage.clear();
</script>

</body>
</html>
