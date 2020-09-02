<?php
declare(strict_types=1);

namespace Skar\LaminasDoctrineORM\Command;

use Doctrine\Migrations\Tools\Console\Command\StatusCommand;

class MigrationsStatus extends StatusCommand {
	use MigrationsCommandTrait;
}
