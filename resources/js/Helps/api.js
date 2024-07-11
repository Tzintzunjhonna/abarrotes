import axios from "axios";

import Swal from "sweetalert2";



/**
 * @category Headers
 */
var config = { headers: { "Content-Type": "application/json" } };
var patch = { headers: { "Content-Type": "multipart/form-data" } };

/**
 * @category Route
 */
console.log(window)
const baseUrl = window.location.origin + "/api/";
// console.log(window)
// if (window.local.user) {
//     const token = window.local.user.access_token;
// // const token_type = window.local.user.access_token_type;
//     axios.defaults.headers.common["Authorization"] = "Bearer " + token
// }

var api = {
    baseUrl: baseUrl,
    baseUrlFiles: baseUrl + "/uploads/",
    loader: function () {
        Swal.fire({
            background: "transparent",
            showCloseButton: false,
            showConfirmButton: false,
            allowOutsideClick: false,
            html: `
            <img src="/images/abarrotes/logo.png" class="w-25">
            <br>
            <div class="spinner-border text-light text-center mb-5" role="status">
            <span class="visually-hidden">Loading...</span>
            </div>
			`,
        });
    },
    get: function (endpoint, params = null) {
        api.loader();
        let url = new URL(baseUrl + endpoint);

        if (params) {
            Object.keys(params).forEach(function (key) {
                url.searchParams.append(key, params[key]);
            });
        }

        return new Promise((resolve, reject) => {
            axios.get(url.href, config).then(
                ({ data }) => {
                    Swal.close();
                    resolve({
                        ...data,
                    });
                },
                (error) => {
                    Swal.close();
                    reject(error.response.data);
                }
            );
        });
    },
    login: function (endpoint, data) {
        api.loader();
        axios.defaults.headers.common = {
            "X-CSRF-TOKEN": document
                .querySelector('meta[name="csrf-token"]')
                .getAttribute("content"),
            "X-Requested-With": "XMLHttpRequest",
        };
        return new Promise((resolve, reject) => {
            axios.post(baseUrl + endpoint, data, config).then(
                ({ data }) => {
                    Swal.close();
                    resolve({ ...data });
                },
                (error) => {
                    Swal.close();
                    reject(error.response.data);
                }
            );
        });
    },
    post: function (endpoint, data) {
        api.loader();
        return new Promise((resolve, reject) => {
            axios.post(baseUrl + endpoint, data, config).then(
                ({ data }) => {
                    Swal.close();
                    resolve({ ...data });
                },
                (error) => {
                    Swal.close();
                    reject(error.response.data);
                }
            );
        });
    },
    patch: function (endpoint, data) {
        api.loader();
        return new Promise((resolve, reject) => {
            axios.patch(baseUrl + endpoint, data, patch).then(
                ({ data }) => {
                    Swal.close();
                    resolve({ ...data });
                },
                (error) => {
                    Swal.close();
                    reject(error.response.data);
                }
            );
        });
    },
    put: function (endpoint, data) {
        api.loader();
        return new Promise((resolve, reject) => {
            axios.put(baseUrl + endpoint, data, config).then(
                ({ data }) => {
                    Swal.close();
                    resolve({ ...data });
                },
                (error) => {
                    Swal.close();
                    reject(error.response.data);
                }
            );
        });
    },
    delete: function (endpoint) {
        api.loader();
        return new Promise((resolve, reject) => {
            axios.delete(baseUrl + endpoint, config).then(
                ({ data }) => {
                    Swal.close();
                    resolve({ ...data });
                },
                (error) => {
                    Swal.close();
                    reject(error.response.data);
                }
            );
        });
    },
};
export default api;
