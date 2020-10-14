<?php

/**
 * @file
 * Production configuration feature.
 */

/**
 * Disable all error messages.
 */
$config['system.logging']['error_level'] = 'hide';

/**
 * System performances.
 */
$config['system.performance']['css']['preprocess'] = TRUE;
$config['system.performance']['css']['gzip'] = TRUE;
$config['system.performance']['js']['preprocess'] = TRUE;
$config['system.performance']['js']['gzip'] = TRUE;
$config['system.performance']['cache']['page']['use_internal'] = TRUE;
$config['system.performance']['response']['gzip'] = TRUE;

/**
 * Trusted host configuration.
 */
$settings['trusted_host_patterns'] = ['^.+\.mah\.fr$',];

/**
 * Managing configuration.
 */
$config['config_split.config_split.prod']['status'] = TRUE;
