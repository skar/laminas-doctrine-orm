<?php
declare(strict_types=1);

namespace Skar\LaminasDoctrineORM\Command;

use Doctrine\Migrations\Tools\Console\Command\ExecuteCommand;

class MigrationsExecute extends ExecuteCommand {
	use MigrationsCommandTrait;
}
