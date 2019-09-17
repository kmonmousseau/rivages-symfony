<?php
namespace Deployer;
require 'recipe/symfony3.php';

// Configuration
set('ssh_type', 'native');
set('ssh_multiplexing', true);
set('composer_action', 'install');
set('composer_options', '{{composer_action}} --verbose --prefer-dist --no-progress --no-interaction --optimize-autoloader --no-dev');
set('repository', 'https://github.com/kmonmousseau/rivages-symfony');

set('dump_assets', true);

add('shared_files', []);
add('shared_dirs', ['web/media', 'web/images/gallery']);
add('writable_dirs', ['web/media']);

// Servers
host('perso')
    ->hostname('perso')
    ->set('branch', 'master')
    ->set('deploy_path', '/var/www/html/benjaminjouet.com')
    ->set('writable_use_sudo', false)
    ->set('writable_chmod_mode', 777)
    ->set('writable_chmod_recursive', false)
    ->set('writable_mode', 'chmod')
    ->stage('prod')
;

/**
 * Upload the shared files
 */
task('upload:files', function () {
    upload('.deploy/parameters.yml', '{{release_path}}/app/config/parameters.yml');
});

before('deploy:vendors', 'upload:files');
