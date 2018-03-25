class ObjectUtils {
    //Warning, this flatens the object. so nested stuff won't behave as you might want.
    static BuildQueryString(obj) {
        let ret = [];
        for(let propertyName in obj) {
            if(obj[propertyName]) {
                ret.push(encodeURIComponent(propertyName) + "=" + encodeURIComponent(obj[propertyName]));
            }
        }
        return ret.join("&");
    }

    //Warning. this flatens the objet. So nested stuff won't behave as you might want.
    static ToFormData(obj) {
        let formData = new FormData();

        for (let key in obj) {
            formData.append(key, obj[key]);
        }

        return formData;
    }
}

export default ObjectUtils;
