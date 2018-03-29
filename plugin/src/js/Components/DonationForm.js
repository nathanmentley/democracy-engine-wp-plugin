import autobind from 'autobind-decorator';
import validator from 'validator';

import BaseComponent from './BaseComponent';

import DonationService from '../Services/DonationService';

//TODO: To support more than one donation form per page. We'll want to be passing in
// a jquery instance of the root form. Instead of just filtering each jquery selector
// on this class name.
const rootClassName = ".democracy-engine-wp-plugin-donation-form-root";

//TODO: cleanup. Move all selectors to be private consts here instead of magic strings below
const submitButtonClassName = ".submit-button";

class DonationForm extends BaseComponent {
    constructor() {
        super();
    }

    //setup jquery listeners
    @autobind
    setup() {
        let $ = this.getJquery();

        $(rootClassName + " " + submitButtonClassName)
            .click(this.submit);

        $(rootClassName + " " + "#donation_billing_address_country_code")
            .change(this.changeCountry).change();
    }

    @autobind
    showAjaxWall() {
        let $ = this.getJquery();

        $(".democracy-engine-wp-plugin-ajax-wall").show();
    }

    @autobind
    hideAjaxWall() {
        let $ = this.getJquery();

        $(".democracy-engine-wp-plugin-ajax-wall").hide();
    }

    @autobind
    showSuccessMessage(message) {
        let $ = this.getJquery();

        $(".democracy-engine-wp-plugin-success .democracy-engine-wp-plugin-success-content").html(message);
        $(".democracy-engine-wp-plugin-donation-form-root").hide();
        $(".democracy-engine-wp-plugin-success").show();
    }

    @autobind
    showErrorMessage(message) {
        let $ = this.getJquery();

        /*
        $(".democracy-engine-wp-plugin-failure .democracy-engine-wp-plugin-failure-content").html(message);
        $(".democracy-engine-wp-plugin-failure").show();
        */

        alert(message);
    }

    @autobind
    submit(evt) {
        evt.preventDefault();
        
        //get form data
        let $ = this.getJquery();
        let form = $(rootClassName);
        let data = form.serializeArray();
        let dataStr = JSON.stringify(data);
        
        if(this.validate(data)) {
            //process ajax promise from our service.
            this.showAjaxWall();
            DonationService.createDonation(dataStr)
                .then((x) => {
                    this.showSuccessMessage(x.data.message);
                })
                .catch((x) => {
                    this.showErrorMessage(x.data.data.message);
                })
                .finally(() => {
                    this.hideAjaxWall();
                });
        }
        
        //prevent non js form submit
        return false;
    }

    @autobind
    validate(data) {
        let $ = this.getJquery();

        let amount1 = this.getValue(data, "donation[amount_option]");
        let amount2 = this.getValue(data, "donation[amount]");
        if(!amount1 && !amount2) {
            alert("Please select a valid amount.");
            return false;
        }
        if(amount1 && !validator.isCurrency(amount1)) {
            alert("Please select a valid amount.");
            return false;
        }
        if(amount2 && !validator.isCurrency(amount2)) {
            alert("Please select a valid amount.");
            return false;
        }

        if(!this.enforceRequiredField(data, "donation[first_name]", "First Name")) { return false; }
        if(!this.enforceRequiredField(data, "donation[last_name]", "Last Name")) { return false; }
        if(!this.enforceRequiredField(data, "donation[billing_address_attributes][address1]", "Adddress 1")) { return false; }
        if(!this.enforceRequiredField(data, "donation[billing_address_attributes][city]", "City")) { return false; }
        if(!this.enforceRequiredField(data, "donation[email]", "Email")) { return false; }
        if(!this.enforceRequiredField(data, "donation[billing_address_attributes][phone_number]", "Phone")) { return false; }
        if(!this.enforceRequiredField(data, "donation[card_number]", "Credit Card Number")) { return false; }
        if(!this.enforceRequiredField(data, "donation[card_verification]", "Credit Card Security Code")) { return false; }
        if(!this.enforceRequiredField(data, "donation[card_expires_on(1i)]", "Expiration Year")) { return false; }
        if(!this.enforceRequiredField(data, "donation[card_expires_on(2i)]", "Expiration Month")) { return false; }
        if(!this.enforceRequiredField(data, "donation[employer]", "Employer")) { return false; }
        if(!this.enforceRequiredField(data, "donation[occupation]", "Ocupation")) { return false; }
        
        let countryCode = this.getValue(data, "donation[billing_address_attributes][country_code]");
        if(countryCode == 'US' || countryCode == 'CA') {
            if(!this.enforceFieldFormat(data, "donation[billing_address_attributes][zip]", "Postal Code", (x) => validator.isPostalCode(x, 'any'))) { return false; }
            if(countryCode == 'CA') {
                if(!this.enforceRequiredField(data, "donation[billing_address_attributes][state_ca]", "State")) { return false; }
            }
        }

        if(!this.enforceFieldFormat(data, "donation[email]", "Email", validator.isEmail)) { return false; }
        if(!this.enforceFieldFormat(data, "donation[billing_address_attributes][phone_number]", "Phone", (x) => validator.isMobilePhone(x, 'any'))) { return false; }
        if(!this.enforceFieldFormat(data, "donation[card_number]", "Credit Card Number", validator.isCreditCard)) { return false; }
        if(!this.enforceFieldFormat(data, "donation[card_verification]", "Credit Card Security Code", validator.isNumeric)) { return false; }

        if($('#donation_is_confirmed:checked').length == 0) {
            alert("Please confirm the data is accurate.");
            return false;
        }
        if($('#donation_is_de_confirmed:checked').length == 0) {
            alert("Please confirm the democracy engine terms of service.");
            return false;
        }

        return true;
    }

    @autobind
    enforceRequiredField(data, key, title) {
        let value = this.getValue(data, key);
        if(!value) {
            alert(title + " is required.");
            return false;
        }

        return true;
    }

    @autobind
    enforceFieldFormat(data, key, title, logic) {
        let value = this.getValue(data, key);
        if(!logic(value)) {
            alert(title + " is invalid.");
            return false;
        }

        return true;
    }

    @autobind
    getValue(data, key) {
        if(data) {
            let record = data.find((x) => x.name === key);

            if(record) {
                return record.value;
            }
        }

        return "";
    }

    @autobind
    changeCountry(evt) {
        let $ = this.getJquery();

        if($(evt.target).val() === "US" || $(evt.target).val() === "CA") {
            $(rootClassName + " " + ".not-us-or-canada").hide();
            $(rootClassName + " " + ".us-or-canada").show();
            
            if($(evt.target).val() === "US") {
                $(rootClassName + " " + ".us-only").show();
                $(rootClassName + " " + ".canada-only").hide();
            } else {
                $(rootClassName + " " + ".us-only").hide();
                $(rootClassName + " " + ".canada-only").show();
            }
        } else {
            $(rootClassName + " " + ".not-us-or-canada").show();
            $(rootClassName + " " + ".us-or-canada").hide();
        }
    }
}

export default DonationForm;
