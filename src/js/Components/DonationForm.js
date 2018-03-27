import autobind from 'autobind-decorator';

import BaseComponent from './BaseComponent';

import DonationService from '../Services/DonationService';

const rootClassName = ".democracy-engine-wp-plugin-donation-form-root";
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
}

export default DonationForm;
