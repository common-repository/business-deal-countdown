jQuery(document).ready(function() {
    jQuery('#business_deal_countdown_date').datepicker({ dateFormat: "yy-mm-dd" });
    
    jQuery('#business_deal_countdown_enable').click(function() {
        jQuery('#business_deal_countdown_options').toggle('.business_deal_countdown_invisible')
    })
});

