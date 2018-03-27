import ObjectUtils from '../Utils/ObjectUtils';

class BaseService {
    constructor() {}

    static get GET() { return 'GET'; }
    static get POST() { return 'POST'; }
    static get PUT() { return 'PUT'; }
    static get DELETE() { return 'DELETE'; }

    //Because wordpress is uhh... "well designed"... There is a single ajax endpoint.
    //  of course, it doesn't expose that value anywhere, so our php code had to pass it through this
    //  global variable. This pulls it out of the window scope.
    get Url() {
        let url = window.democracy_engine_plugin.ajax_url;

        return url;
    }

    //Abstract. Any real service will need to define which ation the ajax action is registered against
    get Action() {
        return "";
    }

    //The actual ajax logic.
    // In retrospect I should have just used JQuery instead of XMLHttpRequest since jQuery is bundled
    // with wordpress, but I already had this code for other projects... so I just modified it handle
    // wordpress' uniqueness.
    SendRequest(method, postData = null) {
        return new Promise((resolve, reject) => {
            let xhr = new XMLHttpRequest();
            let url = this.Url + "?action=" + this.Action;

            switch(method) {
                case BaseService.GET:
                case BaseService.DELETE:
                    if(postData) {
                        if(typeof postData === 'object') {
                            url = url + "?" + ObjectUtils.BuildQueryString(postData);
                        } else {
                            url = url + "/" + postData;
                        }
                    }
                    break;
                case BaseService.POST:
                case BaseService.PUT:
                    break;
            }

            xhr.open(method, url);

            xhr.setRequestHeader('Content-type', 'application/json');

            xhr.onload = () => {
                if (xhr.status === 200) {
                    let result = JSON.parse(xhr.responseText);

                    resolve(result);
                } else {
                    try {
                        reject({ message: "invalid response.", xhr: xhr, data: JSON.parse(xhr.responseText) });
                    } catch(e) {
                        reject({ message: "invalid response.", xhr: xhr, data: xhr.responseText, exception: e });
                    }
                }
            };

            //TODO: This is ugly. Make less ugly
            if(method === BaseService.DELETE || method === BaseService.GET) {
                xhr.send();
            } else {
                xhr.send(JSON.stringify(postData));
            }
        });
    }
}

//every other JS Service will export a single instance.
// this exports the actual class so we can inherit for actual services.
// but for anything using this it's cleaner to make them singletons... of course I'm sure I'll
// feel differently if I ever attempt to unit test those classes.
export default BaseService;

