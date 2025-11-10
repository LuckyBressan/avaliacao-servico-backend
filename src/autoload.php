<?php
/**
 * PSR-4-compatible autoloader for this project.
 * Maps the prefix "App\\" to the src/ directory.
 */

spl_autoload_register(function (string $class) {
    // project-specific namespace prefix
    $prefix = 'App\\';

    // base directory for the namespace prefix
    $base_dir = __DIR__ . DIRECTORY_SEPARATOR;

    // does the class use the namespace prefix?
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) === 0) {
        // get the relative class name
        $relative_class = substr($class, $len);

        // replace namespace separators with directory separators, append with .php
        $file = $base_dir . str_replace('\\', DIRECTORY_SEPARATOR, $relative_class) . '.php';

        if (is_file($file)) {
            require_once $file;
            return true;
        }
    }

    // fallback: try loading non-namespaced class files (compat with existing layout)
    $dirs = ['', 'model', 'persistencia', 'controller', 'utils', 'db'];
    $classFile = $class . '.php';
    foreach ($dirs as $dir) {
        $path = $base_dir . ($dir === '' ? '' : ($dir . DIRECTORY_SEPARATOR)) . $classFile;
        if (is_file($path)) {
            require_once $path;
            return true;
        }
    }

    return false;
});
