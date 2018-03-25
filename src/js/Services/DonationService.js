import BaseService from './BaseService';

class DonationService extends BaseService {
    constructor() {
        super();
    }
    
    get Action() {
        return "donation_form_ajax";
    }
    
    createDonation() {
        let request = {name: name};

        return this.SendRequest(BaseService.POST, request);
    }
}

let service = new DonationService();
export default service;

