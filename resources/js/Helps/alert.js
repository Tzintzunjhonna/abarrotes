import Swal from 'sweetalert2';

const API_URL = window.location.origin;


// const apiSuccessImageUrl = "images/alerts/icon-check-circle.svg";

// const deleteConfirmationimageUrl = "images/alerts/icon-modal-autorizar.svg";
// const apiErrorimageUrl = "images/alerts/icon-error.svg";

const custom = Swal.mixin({
    customClass: {
        confirmButton: 'btn btn-danger rounded-lg m-2',
        cancelButton: 'btn btn-outline-danger rounded-lg m-2'
    },
    buttonsStyling: false
});
const customConfirmation = Swal.mixin({
    customClass: {
        confirmButton: 'btn btn-danger rounded-lg m-2',
        cancelButton: 'btn btn-outline-dark rounded-lg m-2',
        title: 'bg-gray-100 p-5 text-left',
        actions: 'p-2 w-100 border-top mt-5',
        closeButton: 'fa-x1',
    },
    buttonsStyling: false
});

let config = {
    showCloseButton: true,
    closeButtonHtml: '<i class="fa fa-window-close color-orange" aria-hidden="true"></i>',
    allowOutsideClick: false,
    confirmButtonText: 'Aceptar',
}

let objectIMG = {}

import { useToast } from "vue-toastification";
const toast = useToast();

const alert = {
    toast: (type, message, options) => {
        toast[type](message, {
            ...options,
            timeout: 4000
        });
    },
    message: ({ title, text, imageUrl, options }) => {

        if (imageUrl) {
            config.imageUrl = `${API_URL}/${imageUrl}`
            config.imageWidth = 120
            config.imageHeight = 120
            config.imageAlt = 'Images'
        } else {
            delete config.imageUrl
            delete config.imageWidth
            delete config.imageHeight
            delete config.imageAlt
        }

        config.title = title
        config.text = text
        config.html = text

        config = {
            ...config,
            ...options,
            ...objectIMG
        }
        return custom.fire(config)
    },


    deleteConfirmation: ({ title, text, options }) => {

        config.title = title
        config.input = 'text'
        config.text = text
        config.html = text
        config.inputValidator = (value) => {
            if(value != 'Confirmar') {
                return 'Ingresar la palabra "Confirmar" respetando mayúsculas y minúsculas';
            }          
        }

       const deleteConfig = {
            ...config,
            ...options
        }
        return customConfirmation.fire(deleteConfig)
    },


    apiSuccess: ({ title, description, imageUrl }, options ) => {
        const fixedDescription = replaceAll( description, {
            'éxitosa': 'exitosa',
            'éxitoso': 'exitoso'
        } )

        if (imageUrl) {
            config.imageUrl = `${API_URL}/${imageUrl}`
            config.imageWidth = 120
            config.imageHeight = 120
            config.imageAlt = 'Images'
        } else {
            delete config.imageUrl
            delete config.imageWidth
            delete config.imageHeight
            delete config.imageAlt
            delete config.input
        }

        config.title = title
        config.text = fixedDescription
        config.html = fixedDescription

        config = {
            ...config,
            ...options,
            ...objectIMG
        }
        return custom.fire(config)
    },

    apiError: ( { title, description,error, }, options ) => {       
        var msg = ''
        if(error){
            if(typeof(error)== 'array' ){
                msg = error.join('\n');
            }else
            if(typeof(error)== 'object' ){

                var errores='';
                $.each(error,function(i, item){
                    errores += '* ' + item + ' \n';
                });
                msg = errores;
            }else{
                msg = error 
            }


        }else{
            msg = description 
        }
        

        return custom.fire({
            showCloseButton: true,
            closeButtonHtml: '<i class="fa fa-window-close color-orange" aria-hidden="true"></i>',
            allowOutsideClick: false,
            title: title,
            text: msg,//description ? description: (error.hasOwnProperty('messages') ? error.messages:error),
            html: msg,//description ? description: (error.hasOwnProperty('messages') ? error.messages:error),
            confirmButtonText: 'Cerrar',
            ...options
        })
    }
}


function replaceAll(sentence, wordsToReplace) {
    return Object.keys(wordsToReplace).reduce(
      (f, s, i) =>
        `${f}`.replace(new RegExp(s, 'ig'), wordsToReplace[s]),
        sentence
    )
  }

export default alert;