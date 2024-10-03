<?php

/*----------------------------------------------------------------
 * NEXTLOOP
 * @description
 * Check system meets some minimum requirements before continuing
 *   - PHP Version ( >= 7.2.5)
 *   - Writable directory permissions
 *
 * @updated
 * 26 October 2020
 *---------------------------------------------------------------*/
if (!defined('SANITYCHECKS')) {
    die('Access is not permitted');
}

$errors = 0;
$messages_chmod = '';
$messages_php = '';

$paths = [
    '/updates',
    '/storage',
    '/storage/avatars',
    '/storage/logos',
    '/storage/logos/clients',
    '/storage/logos/app',
    '/storage/files',
    '/storage/temp',
    '/application/storage/app',
    '/application/storage/app/public',
    '/application/storage/cache',
    '/application/storage/cache/data',
    '/application/storage/debugbar',
    '/application/storage/framework',
    '/application/storage/framework/cache',
    '/application/storage/framework/cache/data',
    '/application/storage/framework/sessions',
    '/application/storage/framework/testing',
    '/application/storage/framework/views',
    '/application/storage/logs',
    '/application/bootstrap/cache',
    '/application/storage/app/purifier',
    '/application/storage/app/purifier/HTML',
];

//Subdomain - Subfolder Fix
if (!is_dir(BASE_PATH . '/application')) {
    die('Error! - You cannot access the CRM from this url');
}

//defaults
$messages_chmod = '';

//check directoies
foreach ($paths as $key => $value) {
    if (!is_writable(BASE_PATH . $value)) {
        $messages_chmod .= '<tr><td class="p-l-15">' . BASE_PATH . $value . '</td><td class="x-td-checks" width="40px"><span class="x-checks x-check-failed text-danger font-18"><i class="sl-icon-close"></i></span></td></tr>';
        $errors++;
    }
}

//check minimum php version
if (version_compare(PHP_VERSION, '7.3.0', ">=")) {
    $messages_php = '';
} else {
    $messages_php = '<tr><td class="p-l-15">PHP Version <strong>7.3</strong> or <strong>7.4</strong> </td><td class="x-td-checks" width="40px"><span class="x-checks x-check-failed text-danger font-18"><i class="sl-icon-close"></i></span></td></tr>';
    $messages_php .= '
        <tr><td colspan="2">
        <div class="alert alert-danger">
        This version of Grow CRM requires <strong>PHP 7.3+</strong>.
        <br/>
        <br/>You server is currently running <strong> PHP ' . phpversion() . '</strong>
        <br/><br/>
        You can easily set/change the required <strong>PHP Version</strong> inside your web hosting control panel. <a href="https://growcrm.io/documentation/setting-your-php-version/" target="_blank">(see decumentation for help)</a>
        </div></td></tr>';
    $errors++;
}

//page - general
$page = '
<!DOCTYPE html><html lang="en" class="team"><head><link href="public/vendor/css/bootstrap/bootstrap.min.css" rel="stylesheet"><link href="public/themes/default/css/style.css" rel="stylesheet"><link rel="stylesheet" href="public/vendor/css/vendor.css"></head>
<body class="setup-prechecks"><div class="x-wrapper w-90 max-width-1000">
<div class="col-12 p-t-40 card-no-border"><div class="card"><div class="card-body"><div class="text-center"><h3 class="card-title">GROW CRM</h3>
<h5>System Check</h5><div><img src="public/images/system-checks.png" width="300" alt="system checks failed" /></div><p class="card-text">The following (minimum system requirements) must be met before you can continue. See <a href="https://growcrm.io/documentation/2-installation/" target="_blank">documentation</a> for details.</p>
</div><div class="m-t-20">';

//page - php
if ($messages_php != '') {
    $page .= '</br></br><h5 class="text-info"> PHP version requirements</h5><table class="table table-bordered w-100">' . $messages_php . '</table>';
}

//page - chmod
if ($messages_chmod != '') {
    $page .=
        '</br></br><h5 class="text-info"> The following folders are not writable</h5><table class="table table-bordered w-100">' . $messages_chmod . '</table></div><div class="text-center"><a href="/" class="btn btn-info">Retry</a></div></div></div></div></div>';
}

//page end
$page .= '</body><html>';

//do we have directory errors
if ($errors > 0) {
    die($page);
}
