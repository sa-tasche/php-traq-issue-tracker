<?php
$autoloader = require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../src/version.php';

$whoops = new \Whoops\Run;
$whoops->pushHandler(new \Whoops\Handler\PlainTextHandler);
$whoops->register();

use Avalon\Testing\TestSuite;

$testSuite = new TestSuite(
    Traq\Kernel::class,
    Traq\Database\Seeder::class,
    require __DIR__ . '/../config/config.php'
);

if ($testSuite->codeCoverageEnabled()) {
    $coverageFilter = $testSuite->getCodeCoverage()->filter();
    $coverageFilter->addDirectoryToWhitelist('src');
    $coverageFilter->removeDirectoryFromWhitelist('src/config');
    $coverageFilter->removeDirectoryFromWhitelist('src/Database');
    $coverageFilter->removeDirectoryFromWhitelist('src/Translations');
    $coverageFilter->removeDirectoryFromWhitelist('src/views');
    $coverageFilter->removeFileFromWhitelist('src/version.php');
    // $coverageFilter->removeFileFromWhitelist('src/Kernel.php');
    // $coverageFilter->removeFileFromWhitelist('src/Plugin.php');
}

require __DIR__ . '/helpers/models.php';
require __DIR__ . '/tests/permissions.php';
require __DIR__ . '/tests/models/project.php';
require __DIR__ . '/tests/requests/admin/dashboard.php';
require __DIR__ . '/tests/requests/admin/projects.php';
require __DIR__ . '/tests/requests/projects/roadmap.php';
require __DIR__ . '/tests/requests/projects/listing.php';
require __DIR__ . '/tests/requests/tickets/listing.php';
require __DIR__ . '/tests/requests/tickets/update.php';

$testSuite->run();
