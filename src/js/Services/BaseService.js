import ObjectUtils from '../Utils/ObjectUtils';

class BaseService {
    constructor() {}

    static get GET() { return 'GET'; }
    static get POST() { return 'POST'; }
    static get PUT() { return 'PUT'; }
    static get DELETE() { return 'DELETE'; }

    get Url() {
        let url = window.democracy_engine_plugin.ajax_url;

        return url;
    }

    get Action() {
        return "";
    }

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

export default BaseService;

