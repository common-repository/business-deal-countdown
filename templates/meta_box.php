<style>
    .business_deal_countdown_invisible {
        display: none;
    }
</style>

<div class="postbox">
    <p> 
        &nbsp; Watch other 
        <a target="_blank" href="http://www.ninjapress.net/">
            free plugins
        </a> 
        of our suite. Read the 
        <a target="_blank" href="http://www.ninjapress.net/business-deal-countdown/faq/">
            F.A.Q.
        </a> 
        for questions.
    </p>
</div> 

<p>
    <input type="checkbox"  name="business_deal_countdown_enable" id="business_deal_countdown_enable" <?= $enable == 'on' ? 'checked' : '' ?>>
    <label for="business_deal_countdown_enable">Enable Countdown Business Deal</label>
</p>

<div id="business_deal_countdown_options" class="<?= $enable == 'on' ? '' : 'business_deal_countdown_invisible' ?>">
    <p>
        Add <code>[cbd_timer]</code> where you want the counter to appear
    </p>

    <p>˙
        <label for="c">Data: </label>
        <input type="text" id="business_deal_countdown_date" name="business_deal_countdown_date" value="<?= esc_attr($date); ?>" size="10" />

        <label for="c">Time: </label>
        <input type="time" id="business_deal_countdown_time" name="business_deal_countdown_time" value="<?= esc_attr($time); ?>" />
    </p>

    <p>
        <label for="business_deal_countdown_redirect">Redirect to: </label>
        <input type="text" id="business_deal_countdown_redirect" name="business_deal_countdown_redirect" value="<?= esc_attr($redirect); ?>" size="25" />
    </p>
</div>