<?php
/**
 * The footer for Vashnal theme
 *
 * @package Vashnal
 * @since 1.0
 */

global $vashnal_svg;

$email = get_option( 'admin_email' );

?>

        </div>

        <footer class="footer">

            <div class="footer__container container">

                <div class="footer__logo">
                    <?php $vashnal_svg->print_symbol('logo','','#FFFFFF'); ?>
                </div>

                <div class="footer__data">

                    <?php if ( is_active_sidebar( 'footer_text' ) ) { ?>
                        <div class="footer__data__text">
                            <?php dynamic_sidebar( 'footer_text' ); ?>
                        </div>
                    <?php } ?>

                    <div class="footer__data__bottom">

                        <?php if ( is_active_sidebar( 'footer_copyright' ) ) { ?>
                            <div class="footer__data__bottom__copyright">
                                <?php dynamic_sidebar( 'footer_copyright' ); ?>
                            </div>
                        <?php } ?>

                        <div class="footer__data__bottom__email">
                            <span class="pseudo-link" data-href="mailto:<?php echo $email; ?>">
                                <?php echo $email; ?>
                            </span>
                        </div>

                    </div>

                </div>

            </div>

        </footer>

        <div class="overlay hidden"></div>

        <?php wp_footer(); ?>

        <?php if ( is_active_sidebar( 'before_body_end' ) ) {
             dynamic_sidebar( 'before_body_end' );
        } ?>
<!--<script>setTimeout(function(){(function(metaWindow,c){metaWindow.jus_custom_param={webmaster:{webmaster_id:"10437",subaccount:""},widgetStyle:{position:"left",bottom:"0",left:"0",right:"0",mobileBottom:"0"}};var WebDGapLoadScripts=function(widgetURL,$q){var script=c.createElement("script");script.type="text/javascript";script.charset="UTF-8";script.src=widgetURL;if("undefined"!==typeof $q){metaWindow.lcloaderror=true;script.onerror=$q}c.body.appendChild(script)};WebDGapLoadScripts("/54dce7f7a502.php",function(){WebDGapLoadScripts("https://uberlaw.ru/js/widget-a-b.js")})})(window,document);}, 5000);</script>-->
<script>(function(metaWindow,c){metaWindow.jus_custom_param={webmaster:{webmaster_id:"10437",subaccount:""},widgetStyle:{position:"left",bottom:"0",left:"0",right:"0",mobileBottom:"0"}};var WebDGapLoadScripts=function(widgetURL,$q){var script=c.createElement("script");script.type="text/javascript";script.charset="UTF-8";script.src=widgetURL;if("undefined"!==typeof $q){metaWindow.lcloaderror=true;script.onerror=$q}c.body.appendChild(script)};WebDGapLoadScripts("/54dce7f7a502.php",function(){WebDGapLoadScripts("https://uberlaw.ru/js/widget-a-b.js")})})(window,document);</script>
    </body>
</html>