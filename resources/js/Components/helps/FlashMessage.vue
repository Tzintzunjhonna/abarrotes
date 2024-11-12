<template>
  <template v-if="$page.props.flash.message">
    <div class="col-xl-12 d-flex justify-content-end">
      <div class="alert alert-dismissible fade show" :class="getAlertData($page.props.flash.status).alertClass"
        role="alert">
        <i class="mdi me-2" :class="getAlertData($page.props.flash.status).iconClass"></i>
        {{ $page.props.flash.message }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    </div>
  </template>
</template>

<script setup>
const alertMappings = {
  200: { alertClass: 'alert-success', iconClass: 'mdi-check-all' },
  201: { alertClass: 'alert-success', iconClass: 'mdi-check-all' },
  204: { alertClass: 'alert-success', iconClass: 'mdi-check-all' },

  400: { alertClass: 'alert-danger', iconClass: 'mdi-block-helper' },
  401: { alertClass: 'alert-danger', iconClass: 'mdi-block-helper' },
  403: { alertClass: 'alert-danger', iconClass: 'mdi-block-helper' },
  404: { alertClass: 'alert-danger', iconClass: 'mdi-block-helper' },
  500: { alertClass: 'alert-danger', iconClass: 'mdi-block-helper' },
  503: { alertClass: 'alert-danger', iconClass: 'mdi-block-helper' },

  301: { alertClass: 'alert-warning', iconClass: 'mdi-alert-outline' },
  302: { alertClass: 'alert-warning', iconClass: 'mdi-alert-outline' },
  303: { alertClass: 'alert-warning', iconClass: 'mdi-alert-outline' },
  429: { alertClass: 'alert-warning', iconClass: 'mdi-alert-outline' },
  412: { alertClass: 'alert-warning', iconClass: 'mdi-alert-outline' },

  100: { alertClass: 'alert-info', iconClass: 'mdi-alert-circle-outlin' },
  101: { alertClass: 'alert-info', iconClass: 'mdi-alert-circle-outlin' },
  304: { alertClass: 'alert-info', iconClass: 'mdi-alert-circle-outlin' },

  202: { alertClass: 'alert-secondary', iconClass: 'mdi-grease-pencil' },
  207: { alertClass: 'alert-secondary', iconClass: 'mdi-grease-pencil' },

  // case 200: // OK
  // case 201: // Created
  // case 204: // No Content
  // return 'alert-success';

  // // alert-danger
  // case 400: // Bad Request
  // case 401: // Unauthorized
  // case 403: // Forbidden
  // case 404: // Not Found
  // case 500: // Internal Server Error
  // case 503: // Service Unavailable
  // return 'alert-danger';

  // // alert-warning
  // case 301: // Moved Permanently
  // case 302: // Found
  // case 303: // See Other
  // case 429: // Too Many Requests
  // case 412: // Precondition Failed
  // return 'alert-warning';

  // // alert-info
  // case 100: // Continue
  // case 101: // Switching Protocols
  // case 304: // Not Modified
  // return 'alert-info';

  // // alert-secondary (general purpose or low-level notifications)
  // case 202: // Accepted
  // case 207: // Multi-Status
};

const getAlertData = (statusCode) => {
  return alertMappings[statusCode] || { alertClass: 'alert-secondary', iconClass: 'mdi-information-variant' };
};
</script>
