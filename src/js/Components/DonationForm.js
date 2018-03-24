import autobind from 'autobind-decorator';

import DonationService from '../Services/DonationService';

class DonationForm {
    constructor() {}

    @autobind
    setup() {
        let $ = this.getJquery();

        $(".democracy-engine-wp-plugin-donation-form-root .submit-button")
            .click(this.submit);
    }

    @autobind
    getJquery() {
        let $ = window.jQuery;

        return $;
    }

    @autobind
    submit(evt) {
        DonationService.createDonation()
            .then((x) => {
                console.log(x);
                alert("donation");
            });

        evt.preventDefault();
        return false;
    }
}

export default DonationForm;
