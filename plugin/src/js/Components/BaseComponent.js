import autobind from 'autobind-decorator';

class BaseComponent {
    constructor() {}

    //Using jquery in modern front end development is obviously a weird choice, but
    // since jquery is bundled with wordpress I'm not going to try to re-invent the wheel.
    // so we'll just need to deal with it.
    @autobind
    getJquery() {
        let $ = window.jQuery;

        return $;
    }
}

export default BaseComponent;
