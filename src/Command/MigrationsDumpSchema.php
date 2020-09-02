<?php
declare(strict_types=1);

namespace Skar\LaminasDoctrineORM\Command;

use Doctrine\Migrations\Tools\Console\Command\DumpSchemaCommand;

class MigrationsDumpSchema extends DumpSchemaCommand {
	use MigrationsCommandTrait;
}
