import autobind from 'autobind-decorator';

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

        $(".democracy-engine-wp-plugin-failure .democracy-engine-wp-plugin-failure-content").html(message);
        $(".democracy-engine-wp-plugin-failure").show();
    }

    @autobind
    submit(evt) {
        //get form data
        let $ = this.getJquery();
        let form = $(rootClassName);
        let data = JSON.stringify(form.serializeArray());

        //process ajax promise from our service.
        this.showAjaxWall();
        DonationService.createDonation(data)
            .then((x) => {
                this.showSuccessMessage(x.data.message);
            })
            .catch((x) => {
                console.log(x);
                this.showErrorMessage(x.data.data.message);
            })
            .finally(() => {
                this.hideAjaxWall();
            });

        //prevent default submit behavior
        evt.preventDefault();
        return false;
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
