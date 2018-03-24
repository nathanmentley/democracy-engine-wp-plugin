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
}

export default ObjectUtils;
