<?php
namespace OAF\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;

class CreateCommand extends Command
{
  private $dir;

  public function __construct($dir)
  {
    $this->dir = $dir;
    parent::__construct();
  }
  protected function configure()
  {
    $this
      ->setName('migrate:create')
      ->setDescription('Create new migration file')
      ->addOption('name', null, InputOption::VALUE_REQUIRED, 'Name of the file', 'migration');
  }

  protected function execute(InputInterface $input, OutputInterface $o)
  {
    $dir = $this->dir;
    $name = $input->getOption('name');
    $timestamp = time();
    $data = sprintf('# generated at %d by %s', $timestamp, get_current_user());
    $path = sprintf('%s/%s_%s.sql', $dir, $timestamp, $name);
    $bytes = file_put_contents($path, $data);
    $bn = basename($path);

    $o->writeln(sprintf('Directory: <comment>%s</comment>', $dir));
    $o->writeln(sprintf('Generated: <comment>%s</comment> (%d bytes written)', $bn, $bytes));
  }
}