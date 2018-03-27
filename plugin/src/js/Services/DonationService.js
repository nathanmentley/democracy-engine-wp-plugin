import BaseService from './BaseService';

class DonationService extends BaseService {
    constructor() {
        super();
    }
    
    //defines the action the ajax call is registered under in word press.
    get Action() {
        return "donation_form_ajax";
    }
    
    createDonation(request) {
        return this.SendRequest(BaseService.POST, request);
    }
}

//lets export a single instance. Thus making our JS Service instances singletons
let service = new DonationService();
export default service;

