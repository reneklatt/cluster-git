<?php

namespace ClusterGit\Command\Git;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;

use Symfony\Component\Finder\Finder;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Process\Process;

use ClusterGit\Command\Helper;

/**
 *
 */
abstract class BaseCommand extends Command
{

    use Helper;

    const OPTION_PACKAGE = 'pac';
    const OPTION_PACKAGE_SHORT = 'p';

    /**
     * @var array
     */
    protected $packages = array('all');

    /**
     *
     */
    protected function configure()
    {
        $this->setDescription(
            'This Command can run on working directory and all given packages.
            If option "--pac=XY" is used, it will only run on working directory and
            all given packages.

            Examples:
             - {command} will run on working directory and all packages
             - {command} --pac=ab will run on working directory and package "ab-package"
             - {command} --pac=ab --pac=cd  will run on working directory and packages "ab-package" and "ab-package"
            ');

        $this->addOption(
            self::OPTION_PACKAGE,
            self::OPTION_PACKAGE_SHORT,
            InputOption::VALUE_OPTIONAL | InputOption::VALUE_IS_ARRAY,
            'add packages on which command should run'
        );
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->input = $input;
        $this->output = $output;
        $this->getPackages();
        $this->runGitCommand();
    }

    /**
     * @return array
     */
    protected function getPackages()
    {
        if ($this->input->getOption(self::OPTION_PACKAGE)) {
            $this->packages = $this->input->getOption(self::OPTION_PACKAGE);
        }
        return $this->packages;
    }

    /**
     * @return string
     */
    protected function getCommaSeparatedPackages()
    {
        return implode(', ', $this->getPackages());
    }

    /**
     *
     */
    protected function printLineSeparator()
    {
        $this->info(str_repeat('-', 40));
    }

    /**
     *
     */
    protected function getPackageDirs()
    {
        $finder = new Finder();
        $finder->depth(0)->directories()->in(PACKAGE_DIR);
        if (!in_array('all', $this->getPackages())) {
            $finder->filter($this->getCallback());
        }
        return $finder;
    }

    protected function getCallback()
    {
        return function (\SplFileInfo $file) {
            return (in_array($file->getBasename(), $this->getPackages()));
        };
    }

    /**
     * @param string $dir
     */
    protected function runCommand($dir)
    {
        $dirParts = explode(DIRECTORY_SEPARATOR, $dir);
        $folder = end($dirParts);

        $command = $this->computeCommand();

        $this->printLineSeparator();
        $this->info($command . ' for ' . $folder);
        $this->printLineSeparator();
        $process = new Process($command, $dir);
        $process->run();
        if ($process->isSuccessful()) {
            $this->info($process->getOutput(), false);
        }
    }

    abstract protected function runGitCommand();
    abstract protected function computeCommand();

}
