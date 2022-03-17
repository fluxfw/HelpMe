#!/usr/bin/env php
<?php

(function () : void {
    $twig_root = __DIR__ . "/../../../twig/twig";
    if (!file_exists($twig_root)) {
        $twig_root = __DIR__ . "/../vendor/twig/twig";
    }

    foreach ([$twig_root . "/lib/Twig/Template.php"] as $file) {
        $code = $code_old = file_get_contents($file);

        $code = preg_replace("/(array_key_exists\s*\(\s*[A-Za-z0-9_\$()\s]+\s*,\s*)(\\\$[A-Za-z0-9_]+\s*\))/", "$1(array)$2", $code);

        if ($code !== $code_old) {
            echo "Apply patch for " . $file . "\n";
            file_put_contents($file, $code);
        }
    }
})();
