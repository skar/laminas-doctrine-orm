<?php
declare(strict_types=1);

namespace Skar\LaminasDoctrineORM\Command;

use Doctrine\Migrations\Tools\Console\Command\RollupCommand;

class MigrationsRollup extends RollupCommand {
	use MigrationsCommandTrait;
}
