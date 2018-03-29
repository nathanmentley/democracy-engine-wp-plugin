<div class="democracy-engine-wp-plugin-ajax-wall">
    <div class="democracy-engine-wp-plugin-ajax-wall-back">
    </div>
    <div class="democracy-engine-wp-plugin-spinner">
    </div>
</div>

<div class="democracy-engine-wp-plugin-row democracy-engine-wp-plugin-success">
    <div class="democracy-engine-wp-plugin-col-12 democracy-engine-wp-plugin-success-content">
    </div>
</div>

<form class="democracy-engine-wp-plugin-donation-form-root democracy-engine-wp-plugin-container">
    <div class="democracy-engine-wp-plugin-row democracy-engine-wp-plugin-failure">
        <div class="democracy-engine-wp-plugin-col-12 democracy-engine-wp-plugin-failure-content">
        </div>
    </div>

    <div class="democracy-engine-wp-plugin-row">
        <div class="democracy-engine-wp-plugin-col-12">
            <h4 class="democracy-engine-wp-plugin-header-text">Amount</h4>
        </div>
    </div>

    <div class="democracy-engine-wp-plugin-row">
        <?php foreach($donation_amounts as $amount): ?>
            <div class="democracy-engine-wp-plugin-col-3">
                <input class="donation_amount_option user-success" id="donation_amount_<?=$this->e($amount)?>" name="donation[amount_option]" type="radio" value="<?=$this->e($amount)?>">
                <label class="radio" for="donation_amount_<?=$this->e($amount)?>">$<?=$this->e($amount)?></label>
            </div>
        <?php endforeach; ?>
        <div class="democracy-engine-wp-plugin-col-12">
            <label for="donation_amount_other">Other $</label>
            <input class="text" id="donation_amount_other" name="donation[amount]" placeholder="0.00" type="text">
        </div>
    </div>

    <div class="democracy-engine-wp-plugin-row">
        <div class="democracy-engine-wp-plugin-col-12">
            <h4 class="democracy-engine-wp-plugin-header-text">Personal Info</h4>
        </div>
    </div>

    <div class="democracy-engine-wp-plugin-row">
        <div class="democracy-engine-wp-plugin-col-6">
            <input class="text user-success" id="donation_first_name" name="donation[first_name]" placeholder="First name" type="text">
        </div>
        <div class="democracy-engine-wp-plugin-col-6">
            <input class="text" id="donation_last_name" name="donation[last_name]" placeholder="Last name" type="text">
        </div>
    </div>
    <div class="democracy-engine-wp-plugin-row">
        <div class="democracy-engine-wp-plugin-col-12">
            <select id="donation_billing_address_country_code" name="donation[billing_address_attributes][country_code]">
                <?php foreach($countries as $code => $name): ?>
                    <option value="<?=$this->e($code)?>">
                        <?=$this->e($name)?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
    <div class="democracy-engine-wp-plugin-row">
        <div class="democracy-engine-wp-plugin-col-12">
            <input class="text" id="donation_billing_address_address1" name="donation[billing_address_attributes][address1]" placeholder="Address 1" type="text">
        </div>
        <div class="democracy-engine-wp-plugin-col-12">
            <input class="text" id="donation_billing_address_address2" name="donation[billing_address_attributes][address2]" placeholder="Address 2" type="text">
        </div>
        <div class="democracy-engine-wp-plugin-col-12 not-us-or-canada">
            <input class="text not-us-or-canada hide" id="donation_billing_address_address3" name="donation[billing_address_attributes][address3]" placeholder="Address 3" type="text">
        </div>
    </div>
    <div class="democracy-engine-wp-plugin-row">
        <div class="democracy-engine-wp-plugin-col-4">
            <input class="text" id="donation_billing_address_city" name="donation[billing_address_attributes][city]" placeholder="City" type="text">
        </div>
        <div class="democracy-engine-wp-plugin-col-4 us-or-canada us-only hide" style="display: block;">
            <select id="donation_billing_address_state" name="donation[billing_address_attributes][state_us]">
                <?php foreach($us_states as $code => $name): ?>
                    <option value="<?=$this->e($code)?>">
                        <?=$this->e($name)?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="democracy-engine-wp-plugin-col-4 us-or-canada canada-only hide" style="display: none;">
            <input class="text" id="donation_billing_address_state" name="donation[billing_address_attributes][state_ca]" placeholder="State" type="text">
        </div>
        <div class="democracy-engine-wp-plugin-col-4">
            <input class="text" id="donation_billing_address_zip" name="donation[billing_address_attributes][zip]" placeholder="Postal code" type="text">
        </div>
    </div>
    <div class="democracy-engine-wp-plugin-row">
        <div class="democracy-engine-wp-plugin-col-6">
            <input class="text" id="donation_email" name="donation[email]" placeholder="Email" type="email">
        </div>
        <div class="democracy-engine-wp-plugin-col-6">
            <input class="text" id="donation_billing_address_phone_number" name="donation[billing_address_attributes][phone_number]" placeholder="Phone" type="tel">
        </div>
    </div>
    <div class="democracy-engine-wp-plugin-row">
        <div class="democracy-engine-wp-plugin-col-12">
            <label class="checkbox" for="donation_email_opt_in"><input checked="checked" id="donation_email_opt_in" name="donation[email_opt_in]" type="checkbox" value="1"> Send email updates</label>
        </div>
    </div>
    
    <div class="democracy-engine-wp-plugin-row">
        <div class="democracy-engine-wp-plugin-col-12">
            <h4 class="democracy-engine-wp-plugin-header-text">Employer Info</h4>
        </div>
    </div>

    <div class="democracy-engine-wp-plugin-row">
        <div class="democracy-engine-wp-plugin-col-6">
            <input class="text" id="donation_employer" name="donation[employer]" placeholder="Employer" required="required" type="text">
        </div>
        <div class="democracy-engine-wp-plugin-col-6">
            <input class="text" id="donation_occupation" name="donation[occupation]" placeholder="Occupation" required="required" type="text">
        </div>
    </div>
    <div class="democracy-engine-wp-plugin-row">
        <div class="democracy-engine-wp-plugin-col-12 democracy-engine-wp-plugin-legal-text">
            <span>
                Law requires we ask for your employer and occupation. If you don't have an employer or are retired, put N/A, and if you are self-employed put "self-employed" in employer and describe your occupation.
            </span>
        </div>
    </div>
    
    <div class="democracy-engine-wp-plugin-row">
        <div class="democracy-engine-wp-plugin-col-12">
            <h4 class="democracy-engine-wp-plugin-header-text">Payment Info</h4>
        </div>
    </div>

    <div class="democracy-engine-wp-plugin-row">
        <div class="democracy-engine-wp-plugin-col-8">
            <input class="text" id="donation_card_number" name="donation[card_number]" placeholder="Credit card number" type="text">
        </div>
        <div class="democracy-engine-wp-plugin-col-4 cc">
            <img class="icon-cc" src="//d3n8a8pro7vhmx.cloudfront.net/assets/icons/visa2.gif">
            <img class="icon-cc" src="//d3n8a8pro7vhmx.cloudfront.net/assets/icons/mastercard.gif">
            <img class="icon-cc" src="//d3n8a8pro7vhmx.cloudfront.net/assets/icons/amex.gif">
            <img class="icon-cc" src="//d3n8a8pro7vhmx.cloudfront.net/assets/icons/discover.gif">
        </div>
    </div>
    <div class="democracy-engine-wp-plugin-row">
        <div class="democracy-engine-wp-plugin-col-6">
            <select id="donation_card_expires_on_1i" name="donation[card_expires_on(1i)]">
                <?php foreach($years as $intValue): ?>
                    <option value="<?=$this->e($intValue)?>">
                        <?=$this->e($intValue)?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="democracy-engine-wp-plugin-col-6">
            <select id="donation_card_expires_on_2i" name="donation[card_expires_on(2i)]">
                <?php foreach($months as $intValue => $name): ?>
                    <option value="<?=$this->e($intValue)?>">
                        <?=$this->e($intValue)?> - <?=$this->e($name)?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
    <div class="democracy-engine-wp-plugin-row">
        <div class="democracy-engine-wp-plugin-col-8">
            <input class="text" id="donation_card_verification" name="donation[card_verification]" placeholder="Security code" type="text">
        </div>
    </div>
    <div class="democracy-engine-wp-plugin-row">
        <div class="democracy-engine-wp-plugin-col-12 democracy-engine-wp-plugin-legal-text">
            <label for="is_confirmation_text">
                <strong>Contribution rules</strong>
            </label>
            For each contribution that exceeds $200, either by itself or when added to the contributor’s previous contributions made during the same calendar year, records must identify that contribution by: • Amount; • Date of receipt; and • Contributor’s full name and mailing address, occupation and employer. If a person has already contributed an aggregate amount of over $200 during a calendar year, each subsequent contribution, regardless of amount, must be identified in the same way. 102.9(a)(2). Please note that contributions to authorized committees are aggregated on a calendar-year basis for record keeping purposes, but are aggregated on a per-election basis for purposes of monitoring contribution limits, and on an election-cycle basis for reporting purposes. See 102.9(a)(2), 104.3(a)(4), 110.1(b) and 110.2(b)
        </div>   
        <div class="democracy-engine-wp-plugin-col-12">
            <label class="checkbox padtopless" for="donation_is_confirmed">
                <input class="checkbox" id="donation_is_confirmed" name="donation[is_confirmed]" type="checkbox" value="1"/> I confirm that the above statements are true and accurate.
            </label>
        </div>
        <div class="democracy-engine-wp-plugin-col-12">
            <label class="checkbox" for="donation_is_de_confirmed">
                <input class="checkbox" id="donation_is_de_confirmed" name="donation[is_de_confirmed]" type="checkbox" value="1"/> I agree to the Democracy Engine <a href="http://www.democracyengine.com/subscriber_tos" target="_new">Terms of Service</a> and <a href="http://www.democracyengine.com/subscriber_privacy_policy" target="_new">Privacy Policy</a>. You will not receive any emails from them, they just deliver your donation to us.
            </label>
        </div>
        <div class="democracy-engine-wp-plugin-col-12">
            <label class="checkbox" for="donation_is_private">
                <input class="checkbox" id="donation_is_private" name="donation[is_private]" type="checkbox" value="1" /> Don't publish my donation on the website.
            </label>
        </div>
        <div class="democracy-engine-wp-plugin-col-12">
            <div class="democracy-engine-wp-plugin-col-12 democracy-engine-wp-plugin-legal-text">
                Contributions are <i>not</i> tax deductible.
            </div>
        </div>
        <div class="democracy-engine-wp-plugin-col-12">
            <div class="padtop">
                <input class="submit-button" name="commit" type="submit" value="Process Donation" />
            </div>
        </div>
    </div>
</form>
