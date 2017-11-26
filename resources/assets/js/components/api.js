import {browserHistory} from 'react-router';
import fetchIntercept from 'fetch-intercept';

fetchIntercept.register({
    request: function (url, config) {
        config = config || {};
        const newConfig = Object.assign({}, config, {
            credentials: "same-origin",
            headers: Object.assign({}, config.headers || {}, {
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            })
        });
        return [url, newConfig];
    },
    response: function (response) {
        if (response.status == 401 && response.url.indexOf('/login') < 0) {
            browserHistory.push('/login');
            return Promise.reject(response);
        }
        return response;
    }
});
