<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Thin wrapper around the AWS S3 SDK for DigitalOcean Spaces.
 *
 * Uploaded files are still processed on local disk first (verot/class.upload.php
 * needs a filesystem path to resize images into), then pushed here under the
 * same relative path used historically (e.g. "uploads/student/2026/09/foo.jpg"),
 * so DB rows that already store that relative path keep working unchanged.
 */
class Spaces
{
    private $client;
    private $enabled = false;
    private $bucket = '';
    private $cdn_url = '';
    private $prefix = '';

    public function __construct()
    {
        $ci = &get_instance();
        $ci->config->load('spaces');
        $cfg = $ci->config->item('spaces');

        $this->enabled = !empty($cfg['enabled']) && $cfg['key'] && $cfg['secret'] && $cfg['bucket'] && $cfg['endpoint'];
        $this->bucket  = $cfg['bucket'];
        $this->cdn_url = rtrim($cfg['cdn_url'] ?: $cfg['endpoint'], '/');
        $this->prefix  = trim($cfg['folder'] ?? '', '/');

        if ($this->enabled) {
            $this->client = new \Aws\S3\S3Client([
                'version'                 => 'latest',
                'region'                  => $cfg['region'],
                'endpoint'                => $cfg['endpoint'],
                'use_path_style_endpoint' => !empty($cfg['path_style']),
                'credentials'             => [
                    'key'    => $cfg['key'],
                    'secret' => $cfg['secret'],
                ],
            ]);
        }
    }

    public function isEnabled()
    {
        return $this->enabled;
    }

    /**
     * Prefix a relative path/key with the configured bucket subfolder, if any.
     * Keeps the DB-stored relative path (e.g. "uploads/student/...") unchanged —
     * only the actual bucket key gets the "DO_SPACES_FOLDER/" prefix.
     */
    private function prefixedKey($key)
    {
        $key = ltrim($key, '/');
        return $this->prefix ? $this->prefix . '/' . $key : $key;
    }

    /**
     * Upload a local file to Spaces under the given relative key.
     *
     * @param string $localPath absolute path of the already-processed local file
     * @param string $key       relative path/key to store it under, e.g. "uploads/student/2026/09/foo.jpg"
     */
    public function putFile($localPath, $key)
    {
        if (!$this->enabled || !is_file($localPath)) {
            return false;
        }

        try {
            $this->client->putObject([
                'Bucket'     => $this->bucket,
                'Key'        => $this->prefixedKey($key),
                'SourceFile' => $localPath,
                'ACL'        => 'public-read',
            ]);
            return true;
        } catch (\Throwable $e) {
            log_message('error', 'Spaces upload failed for ' . $key . ': ' . $e->getMessage());
            return false;
        }
    }

    public function deleteFile($key)
    {
        if (!$this->enabled || !$key) {
            return false;
        }

        try {
            $this->client->deleteObject([
                'Bucket' => $this->bucket,
                'Key'    => $this->prefixedKey($key),
            ]);
            return true;
        } catch (\Throwable $e) {
            log_message('error', 'Spaces delete failed for ' . $key . ': ' . $e->getMessage());
            return false;
        }
    }

    public function url($key)
    {
        return $this->cdn_url . '/' . $this->prefixedKey($key);
    }
}
