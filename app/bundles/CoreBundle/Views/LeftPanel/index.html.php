<?php
/**
 * @package     Mautic
 * @copyright   2014 Mautic Contributors. All rights reserved.
 * @author      Mautic
 * @link        http://mautic.org
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

// $pinned = ($app->getSession()->get("left-panel", 'default') == 'unpinned') ? ' unpinned' : '';
?>
<!-- start: sidebar-header -->
<div class="sidebar-header">
    <!-- brand -->
    <a class="mautic-brand" href="#">
        <!-- logo figure -->
        <svg xmlns="http://www.w3.org/2000/svg" class="mautic-logo-figure" viewBox="0 0 29.4 29.4"><path d="M14.7,0A14.7,14.7,0,1,0,29.4,14.7,14.7,14.7,0,0,0,14.7,0ZM27.4,14.7a12.6,12.6,0,0,1-1.2,5.5H15.1l8-15A12.7,12.7,0,0,1,27.4,14.7ZM2,14.7A12.6,12.6,0,0,1,3.2,9.2H14.3l-8,15A12.7,12.7,0,0,1,2,14.7ZM14.7,27.4a12.6,12.6,0,0,1-6.8-2L17.6,7.2H4.4A12.7,12.7,0,0,1,21.5,4L11.8,22.2H25A12.7,12.7,0,0,1,14.7,27.4Z" style="fill:#ec6c00"></path></svg>
        <!--/ logo figure -->
        <!-- logo text -->
	<svg version="1.2" class="mautic-logo-text mnl-3"  xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
	 x="0px" y="0px" viewBox="0 0 200 70" xml:space="preserve">
		<g id="XMLID_82_">
		<path id="XMLID_10_" fill="#231916" d="M16.6,18.4v-3.9h21.3L21,50.8h16v3.9H14.8l17-36.4H16.6z"/>
		<path id="XMLID_7_" fill="#231916" d="M69.5,44.9l3.2,1.7c-1.1,2.1-2.3,3.8-3.7,5c-1.4,1.3-3,2.3-4.7,2.9c-1.7,0.7-3.7,1-5.9,1
		c-4.9,0-8.7-1.6-11.4-4.8C44.4,47.6,43,44,43,39.9c0-3.8,1.2-7.2,3.5-10.1c2.9-3.8,6.9-5.6,11.8-5.6c5.1,0,9.1,1.9,12.2,5.8
		c2.2,2.7,3.3,6.1,3.3,10.2H46.9c0.1,3.5,1.2,6.3,3.3,8.5c2.1,2.2,4.8,3.3,7.9,3.3c1.5,0,3-0.3,4.4-0.8c1.4-0.5,2.7-1.2,3.7-2.1
		C67.3,48.2,68.4,46.8,69.5,44.9z M69.5,36.9c-0.5-2-1.3-3.7-2.2-4.9c-1-1.2-2.3-2.2-3.9-3c-1.6-0.7-3.3-1.1-5.1-1.1
		c-2.9,0-5.4,0.9-7.5,2.8c-1.5,1.4-2.7,3.4-3.5,6.2H69.5z"/>
		<path id="XMLID_5_" fill="#231916" d="M80.9,25h3.8v5.3c1.5-2,3.2-3.6,5.1-4.6c1.9-1,3.9-1.5,6.1-1.5c2.2,0,4.2,0.6,5.9,1.7
		c1.7,1.1,3,2.7,3.8,4.6c0.8,1.9,1.2,4.9,1.2,9v15.3h-3.8V40.5c0-3.4-0.1-5.7-0.4-6.9c-0.4-2-1.3-3.5-2.6-4.5
		c-1.3-1-2.9-1.5-4.9-1.5c-2.3,0-4.4,0.8-6.2,2.3c-1.8,1.5-3,3.4-3.6,5.7c-0.4,1.5-0.5,4.2-0.5,8.1v10.9h-3.8V25z"/>
		<path id="XMLID_3_" fill="#231916" d="M113.9,54.8l5.8-40.3h0.7l16.4,33.1L153,14.5h0.6l5.8,40.3h-4l-4-28.8l-14.3,28.8h-1
		l-14.4-29l-4,29H113.9z"/>
		<path id="XMLID_1_" fill="#231916" d="M163.4,18.4v-3.9h22.1v3.9h-9v36.4h-4.1V18.4H163.4z"/>
		</g>
	</svg>
        <!--/ logo text -->
    </a>
    <!--/ brand -->
</div>
<!--/ end: sidebar-header -->

<!-- start: sidebar-content -->
<div class="sidebar-content">
    <!-- scroll-content -->
    <div class="scroll-content slimscroll">
        <!-- start: navigation -->
        <nav class="nav-sidebar">
            <?php echo $view['knp_menu']->render('main', array("menu" => "main")); ?>

            <!-- start: left nav -->
            <ul class="nav sidebar-left-dark">
                <li class="hidden-xs">
                    <a href="javascript:void(0)" data-toggle="minimize" class="sidebar-minimizer"><span class="direction icon pull-left fa"></span><span class="nav-item-name pull-left text"><?php echo $view['translator']->trans('mautic.core.menu.left.collapse'); ?></span></a>
                </li>
            </ul>
            <!--/ end: left nav -->

        </nav>
        <!--/ end: navigation -->
    </div>
    <!--/ scroll-content -->
</div>
<!--/ end: sidebar-content -->
