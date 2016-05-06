<?php

class CIValetDriver extends ValetDriver
{
    /**
     * Determine if the driver serves the request.
     *
     * @param  string  $sitePath
     * @param  string  $siteName
     * @param  string  $uri
     * @return void
     */
    public function serves($sitePath, $siteName, $uri)
    {
        if (file_exists($sitePath.'/system/core/CodeIgniter.php')) {
            return true;
        }

        return false;
    }

    /**
     * Determine if the incoming request is for a static file.
     *
     * @param  string  $sitePath
     * @param  string  $siteName
     * @param  string  $uri
     * @return string|false
     */
    public function isStaticFile($sitePath, $siteName, $uri)
    {
        if (file_exists($sitePath.$uri) &&
            ! is_dir($sitePath.$uri) &&
            pathinfo($sitePath.$uri)['extension'] != 'php') {
            return $sitePath.$uri;
        }
        return false;
    }

    /**
     * Get the fully resolved path to the application's front controller.
     *
     * @param  string  $sitePath
     * @param  string  $siteName
     * @param  string  $uri
     * @return string
     */
    public function frontControllerPath($sitePath, $siteName, $uri)
    {
        if (isset($_SERVER['HTTP_X_ORIGINAL_HOST'])) {
            $_SERVER['HTTP_HOST'] = $_SERVER['HTTP_X_ORIGINAL_HOST'];
        }
        $_SERVER['SCRIPT_NAME'] = '/index.php';
        if (strpos($_SERVER['REQUEST_URI'], '/index.php') === 0) {
            $_SERVER['REQUEST_URI'] = substr($_SERVER['REQUEST_URI'], 10);
        }
        if ($uri === '') {
            $uri = '/';
        }
        if ($uri === '/installer.php') {
            return $sitePath.'/installer.php';
        }
        if (file_exists($indexPath = $sitePath.'/index.php')) {
            return $indexPath;
        }
        if (file_exists($indexPath = $sitePath.'/public/index.php')) {
            return $indexPath;
        }
    }
}
