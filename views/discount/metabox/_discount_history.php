<?php
/**
 * @var SLN_Plugin $plugin
 * @var SLN_Settings $settings
 * @var SLN_Metabox_Helper $helper
 * @var SLB_Discount_Wrapper_Discount $discount
 * @var string $postType
 *
 */
?>

<div class="sln-box--sub row">
    <div class="col-xs-12"><h2 class="sln-box-title"><?php echo sprintf(__('Total usage ( %d )', 'salon-booking-system'), $discount->getTotalUsagesNumber()) ?></h2></div>
    <div class="col-xs-12 sln-table">
        <?php
        global $post;
        $_post = $post;

        $_GET['post_type'] = SLN_Plugin::POST_TYPE_BOOKING;
        get_current_screen()->add_option('post_type', SLN_Plugin::POST_TYPE_BOOKING);
        get_current_screen()->id = 'edit-sln_booking';
        get_current_screen()->post_type = SLN_Plugin::POST_TYPE_BOOKING;

        /** @var SLB_Discount_Admin_DiscountsHistoryList $wp_list_table */
        $wp_list_table = new SLB_Discount_Admin_DiscountsHistoryList();

        $wp_list_table->prepare_items();

        $wp_list_table->display();

        $post = $_post;
        ?>
    </div>
</div>

<div class="sln-clear"></div>
<?php do_action('sln.template.discount_history.metabox', $discount); ?>