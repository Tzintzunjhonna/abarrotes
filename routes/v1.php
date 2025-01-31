<?php

use Illuminate\Support\Facades\Route;

/**
 * Se importan los archivos con las rutas de cada modulo.
 */

require_once "v1/admin/users/v1.php";
require_once "v1/admin/roles/v1.php";
require_once "v1/admin/company/v1.php";
require_once "v1/admin/permissions/v1.php";
require_once "v1/app/providers/v1.php";
require_once "v1/app/customers/v1.php";
require_once "v1/app/products/v1.php";
require_once "v1/app/configuration/v1.php";
require_once "v1/app/sales_management/v1.php";