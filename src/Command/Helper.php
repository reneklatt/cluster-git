<?php

namespace ClusterGit\Command;

/**
 * @author René Klatt <rene.klatt@gmx.net>
 */
trait Helper
{

    /**
     * @var InputInterface
     */
    protected $input;

    /**
     * @var OutputInterface
     */
    protected $output;

    /**
     * @param string $message
     */
    protected function info($message, $wrapInInfoTags = true)
    {
        if ($wrapInInfoTags) {
            $message = '<info>' . $message . '</info>';
        }
        $this->output->writeln($message);
    }

    /**
     * @param string $message
     */
    protected function error($message)
    {
        $message = '<error>' . $message . '</error>';
        $this->output->writeln($message);
    }

    /**
     * @param $question
     * @param null $default
     * @return mixed
     */
    protected function ask($question, $default = null)
    {
        $dialog = $this->getHelperSet()->get('dialog');
        return $dialog->ask($this->output, $question, $default);
    }

}
