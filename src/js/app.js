import DonationForm from './Components/DonationForm';

document.addEventListener("DOMContentLoaded", () => { 
    let $ = window.jQuery;
    
    if($(".democracy-engine-wp-plugin-donation-form-root").length) {
        let donationForm = new DonationForm();

        donationForm.setup();
    }
});
