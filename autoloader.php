<?php
spl_autoload_register(
    function (string $className) {
        $prefix = 'App\\';
        $base_dir = __DIR__ . '/src/';
        $len = strlen($prefix);

        if (strncmp($prefix, $className, $len) !== 0) {
            return;
        }

        $relative_class = substr($className, $len);

        $parts = explode("\\", $relative_class);

        foreach ($parts as $key => $part) {
            $parts[$key] = ucfirst($part);
        }

        $relative_class = join("\\", $parts);

        $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

        if (file_exists($file)) {
            require_once $file;
        }
    }
);