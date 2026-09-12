<?php

defined('BASEPATH') or exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| DigitalOcean Spaces (S3-compatible) file storage
|--------------------------------------------------------------------------
|
| Uploaded files (photos, certificates, attachments) are pushed here instead
| of relying on the app server's local disk, so any droplet behind the load
| balancer can serve them.
|
| Configure via .env on each app server (not by editing this file), so no
| secret is committed to git and every droplet shares the same config.
| See .env.example for the full list:
|
|   DO_SPACES_ENABLED  = true
|   DO_SPACES_KEY      = <Spaces access key>
|   DO_SPACES_SECRET   = <Spaces secret key>
|   DO_SPACES_BUCKET   = eprap
|   DO_SPACES_REGION   = lon1
|   DO_SPACES_ENDPOINT = https://lon1.digitaloceanspaces.com
|   DO_SPACES_CDN_URL  = https://eprap.lon1.cdn.digitaloceanspaces.com
|   DO_SPACES_PATH_STYLE = false
|   DO_SPACES_FOLDER   = eprap
|
| DO_SPACES_PATH_STYLE controls S3 addressing style: false (default) uses
| virtual-hosted-style (bucket.endpoint), which is what real DO Spaces
| expects. Set it to true only against an endpoint with no wildcard DNS for
| bucket subdomains — e.g. a local MinIO instance for dev/testing.
|
| DO_SPACES_FOLDER prefixes every object key with this subfolder — use it
| when sharing one bucket across multiple projects. Leave blank to store
| files at the bucket root. DO_SPACES_CDN_URL should point at the bucket's
| root (not include this folder) since it gets appended automatically.
|
| Leaving DO_SPACES_ENABLED unset (or false) keeps the original local-disk
| behaviour, so local development needs no Spaces account.
|
*/

$config['spaces'] = [
    'enabled'    => filter_var(env('DO_SPACES_ENABLED'), FILTER_VALIDATE_BOOLEAN),
    'key'        => env('DO_SPACES_KEY', ''),
    'secret'     => env('DO_SPACES_SECRET', ''),
    'bucket'     => env('DO_SPACES_BUCKET', ''),
    'region'     => env('DO_SPACES_REGION', 'lon1'),
    'endpoint'   => env('DO_SPACES_ENDPOINT', ''),
    'cdn_url'    => env('DO_SPACES_CDN_URL', ''),
    'path_style' => filter_var(env('DO_SPACES_PATH_STYLE'), FILTER_VALIDATE_BOOLEAN),
    'folder'     => env('DO_SPACES_FOLDER', ''),
];
