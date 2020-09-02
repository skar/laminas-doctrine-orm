<?php
declare(strict_types=1);

namespace Skar\LaminasDoctrineORM\Command;

use Doctrine\Migrations\Tools\Console\Command\MigrateCommand;

class MigrationsMigrate extends MigrateCommand {
	use MigrationsCommandTrait;
}
