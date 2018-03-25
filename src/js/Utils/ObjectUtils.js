class ObjectUtils {
    static BuildQueryString(obj) {
        let ret = [];
        for(let propertyName in obj) {
            if(obj[propertyName]) {
                ret.push(encodeURIComponent(propertyName) + "=" + encodeURIComponent(obj[propertyName]));
            }
        }
        return ret.join("&");
    }

    static ToFormData(obj) {
        let formData = new FormData();

        for (let key in obj) {
            formData.append(key, obj[key]);
        }

        return formData;
    }
}

export default ObjectUtils;
