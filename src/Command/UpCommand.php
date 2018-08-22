<?php
namespace OAF\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Finder\Finder;
use Aura\Sql\ExtendedPdo;


class UpCommand extends Command
{
  private $pdo;
  private $dir;

  public function __construct($dir, \PDO $pdo)
  {
    $this->pdo = $pdo;
    $this->dir = $dir;

    parent::__construct();
  }

  protected function getMigrations()
  {
    return glob(sprintf('%s/*.sql', $this->dir));
  }

  protected function configure()
  {
    $this
      ->setName('migrate:run')
      ->setDescription('Run migrations');
  }

  protected function execute(InputInterface $input, OutputInterface $output)
  {
    $migrations = $this->getMigrations();

    $output->writeln(sprintf('Found %d migration(s)', count($migrations)));

    foreach ($migrations as $migration) {
      $basename = basename($migration);
      $sql = file_get_contents($migration);

      $output->write(sprintf('Running %s...', $basename));
      $affected = $this->pdo->exec($sql);
      $output->write('[<info>done</info>]');
      $output->writeln('');
    }

    $output->writeln('<info>Migration completed</info>');
  }
}