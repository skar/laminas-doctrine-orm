<?php
declare(strict_types=1);

namespace Skar\LaminasDoctrineORM\Command;

use Doctrine\Migrations\Tools\Console\Command\VersionCommand;

class MigrationsVersion extends VersionCommand {
	use MigrationsCommandTrait;
}
