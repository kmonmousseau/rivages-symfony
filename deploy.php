<?php
namespace Deployer;
require 'recipe/symfony3.php';

// Configuration
set('ssh_type', 'native');
set('ssh_multiplexing', true);
set('composer_action', 'install');
set('composer_options', '{{composer_action}} --verbose --prefer-dist --no-progress --no-interaction --optimize-autoloader --no-dev');
set('repository', 'https://github.com/kmonmousseau/rivages-symfony');

add('shared_files', []);
add('shared_dirs', ['node_modules']);
add('writable_dirs', []);

// Servers
host('perso')
    ->hostname('perso')
    ->set('branch', 'master')
    ->set('deploy_path', '/var/www/benjaminjouet.com')
    ->set('writable_use_sudo', true)
    ->set('writable_chmod_mode', 777)
    ->set('writable_mode', 'chmod')
    ->stage('prod')
;

/**
 * Upload the shared files
 */
task('upload:files', function () {
    upload('.deploy/parameters.yml', '{{release_path}}/app/config/parameters.yml');
});

task('reload:php', function () {
    run('sudo service php7.0-fpm reload');
});

before('deploy:vendors', 'upload:files');
after('deploy:symlink', 'reload:php');