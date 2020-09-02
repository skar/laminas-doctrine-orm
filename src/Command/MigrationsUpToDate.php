<?php
declare(strict_types=1);

namespace Skar\LaminasDoctrineORM\Command;

use Doctrine\Migrations\Tools\Console\Command\UpToDateCommand;

class MigrationsUpToDate extends UpToDateCommand {
	use MigrationsCommandTrait;
}
