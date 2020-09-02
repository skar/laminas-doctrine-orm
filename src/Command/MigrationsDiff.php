<?php
declare(strict_types=1);

namespace Skar\LaminasDoctrineORM\Command;

use Doctrine\Migrations\Tools\Console\Command\DiffCommand;

class MigrationsDiff extends DiffCommand {
	use MigrationsCommandTrait;
}
