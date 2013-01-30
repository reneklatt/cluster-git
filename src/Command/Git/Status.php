<?php

namespace ClusterGit\Command\Git;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;


class Status extends BaseCommand
{

    const GIT_COMMAND = 'status';

    const OPTION_SHORT = 'short';
    const OPTION_SHORT_SHORT = 's';
    const OPTION_BRANCH = 'branch';
    const OPTION_BRANCH_SHORT = 'b';

    /**
     *
     */
    protected function configure()
    {
        parent::configure();
        $this->setName(self::GIT_COMMAND)
            ->setHelp('<info>cgit status -h</info>');

        $this->addOption(self::OPTION_SHORT, self::OPTION_SHORT_SHORT, InputOption::VALUE_NONE, 'show status concisely');
        $this->addOption(self::OPTION_BRANCH, self::OPTION_BRANCH_SHORT, InputOption::VALUE_NONE, 'show branch information');
    }

    /**
     *
     */
    protected function runGitCommand()
    {
        $this->info('Run git command for project-root and "' . $this->getCommaSeparatedPackages() . '" project-a packages.',
            false
        );
        $this->loopAllDirectories();
    }

    protected function loopAllDirectories()
    {
        $this->runCommand(ROOT_DIR);
        foreach($this->getPackageDirs() as $packageDir){
            $this->runCommand($packageDir);
        }

    }

    /**
     * @return string
     */
    protected function computeCommand()
    {
        $command = str_replace('-', ' ', self::GIT_COMMAND);
//        if ($this->input->hasOption(self::))
        return $command;
    }

}