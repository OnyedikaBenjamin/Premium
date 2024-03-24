import { displayError } from "./messages";
if (document.getElementsByTagName('meta')["baseUrl"] !== undefined) {
    baseUrl = window.baseUrl = document.getElementsByTagName('meta')["baseUrl"].content;
}
export const post = (endpoint,data ,headers = {
    headers: { 'Content-Type': 'application/json' }
}) => {
    var url = baseUrl + '/api/' + endpoint
    return new Promise((resolve, reject, headers) => {
        axios.post(url, data, headers)
            .then((data) => {
                if (data.data.status_code !== undefined && data.data.status_code === 403) {
                    displayError(data.data.message);
                    vm.$router.push({ name: "403"});
                }
                resolve(data)
            })
            .catch((error) => {
                reject(error)
            });
    })
}

export const get = (endpoint, data ,headers = {
    headers: { 'Content-Type': 'application/json' }
}) => {
    var url = baseUrl + '/api/' + endpoint
    return new Promise((resolve, reject) => {
        axios.get(url,data)
        .then((data) => {
            if (data.data.status_code !== undefined && data.data.status_code === 403) {
                displayError(data.data.message);
                vm.$router.push({ name: "403"});
            }
            resolve(data)
        })
        .catch((error) => {
            reject(error)
        });
    })
}