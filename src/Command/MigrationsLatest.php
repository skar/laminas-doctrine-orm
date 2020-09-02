<?php
declare(strict_types=1);

namespace Skar\LaminasDoctrineORM\Command;

use Doctrine\Migrations\Tools\Console\Command\LatestCommand;

class MigrationsLatest extends LatestCommand {
	use MigrationsCommandTrait;
}
