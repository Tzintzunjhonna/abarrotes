import { createApp } from "vue"; // Importar 'createApp' en Vue 3
import axios from "axios";
import Swal from "sweetalert2";
import LoadingScreen from "@/Components/helps/loading.vue";

const storedUser = localStorage.getItem("token");

if (storedUser) {
    axios.defaults.headers.common["Authorization"] = `Bearer ${storedUser}`;
}

/**
 * @category Headers
 */
var config = { headers: { "Content-Type": "application/json" } };
var patch = { headers: { "Content-Type": "multipart/form-data" } };

/**
 * @category Route
 */
const baseUrl = window.location.origin + "/api/";

var api = {
    baseUrl: baseUrl,
    baseUrlFiles: baseUrl + "/uploads/",

    loader: function (isLoading) {

        const loaderApp = createApp(LoadingScreen);
        const loaderInstance = loaderApp.mount(document.createElement("div"));

        document.body.appendChild(loaderInstance.$el);

        if (isLoading) {
            loaderInstance.startLoading();
        } else {
            loaderInstance.stopLoading();
        }
    },
    get: function (endpoint, params = null) {
        // api.loader(true);
        let url = new URL(baseUrl + endpoint);

        if (params) {
            Object.keys(params).forEach(function (key) {
                url.searchParams.append(key, params[key]);
            });
        }

        return new Promise((resolve, reject) => {
            axios.get(url.href, config).then(
                ({ data }) => {
                    // api.loader(false);
                    resolve({
                        ...data,
                    });
                },
                (error) => {
                    console.log("get error");
                    // api.loader(false);
                    reject(error.response.data);
                }
            );
        });
    },
    login: function (endpoint, data) {
        // api.loader(true);
        axios.defaults.headers.common = {
            "X-CSRF-TOKEN": document
                .querySelector('meta[name="csrf-token"]')
                .getAttribute("content"),
            "X-Requested-With": "XMLHttpRequest",
        };
        return new Promise((resolve, reject) => {
            axios.post(baseUrl + endpoint, data, config).then(
                ({ data }) => {
                    // api.loader(false);
                    resolve({ ...data });
                },
                (error) => {
                    // api.loader(false);
                    reject(error.response.data);
                }
            );
        });
    },
    post: function (endpoint, data, config_data = config) {
        // api.loader(true);
        return new Promise((resolve, reject) => {
            axios.post(baseUrl + endpoint, data, config_data).then(
                ({ data }) => {
                    // api.loader(false);
                    resolve({ ...data });
                },
                (error) => {
                    // api.loader(false);
                    reject(error.response.data);
                }
            );
        });
    },
    patch: function (endpoint, data) {
        // api.loader(true);
        return new Promise((resolve, reject) => {
            axios.patch(baseUrl + endpoint, data, patch).then(
                ({ data }) => {
                    // api.loader(false);
                    resolve({ ...data });
                },
                (error) => {
                    // api.loader(false);
                    reject(error.response.data);
                }
            );
        });
    },
    put: function (endpoint, data, config_data = config) {
        // api.loader(true);
        return new Promise((resolve, reject) => {
            axios.put(baseUrl + endpoint, data, config_data).then(
                ({ data }) => {
                    // api.loader(false);
                    resolve({ ...data });
                },
                (error) => {
                    // api.loader(false);
                    reject(error.response.data);
                }
            );
        });
    },
    delete: function (endpoint) {
        // api.loader(true);
        return new Promise((resolve, reject) => {
            axios.delete(baseUrl + endpoint, config).then(
                ({ data }) => {
                    // api.loader(false);
                    resolve({ ...data });
                },
                (error) => {
                    // api.loader(false);
                    reject(error.response.data);
                }
            );
        });
    },
};
export default api;
