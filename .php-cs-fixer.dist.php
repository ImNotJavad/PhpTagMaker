<?php

// This configuration uses the "amphp/php-cs-fixer-config" package,
// which provides a standard, modern coding style for PHP projects.
// It helps maintain consistency across the entire codebase.

$config = new Amp\CodeStyle\Config;
$config->getFinder()
    ->in(__DIR__ . "/src")
    ->in(__DIR__ . "/tests");

$config->setCacheFile(__DIR__ . '/.php-cs-fixer.cache');

// FIX: Allow running on unsupported PHP versions (like PHP 8.4+).
// The tool may not be fully compatible with the newest syntax, but this
// setting is necessary to allow development on newer PHP runtimes.
$config->setUnsupportedPhpVersionAllowed(true);

return $config;
