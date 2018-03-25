//polyfill for old browsers
import "babel-polyfill";

//import our components
import DonationForm from './Components/DonationForm';

//on document loaded lets setup all components we find.
document.addEventListener("DOMContentLoaded", () => { 
    let $ = window.jQuery;
    
    //todo:
    if($(".democracy-engine-wp-plugin-donation-form-root").length) {
        let donationForm = new DonationForm();

        donationForm.setup();
    }
});
