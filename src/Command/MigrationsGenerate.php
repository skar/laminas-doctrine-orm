<?php
declare(strict_types=1);

namespace Skar\LaminasDoctrineORM\Command;

use Doctrine\Migrations\Tools\Console\Command\GenerateCommand;

class MigrationsGenerate extends GenerateCommand {
	use MigrationsCommandTrait;
}
