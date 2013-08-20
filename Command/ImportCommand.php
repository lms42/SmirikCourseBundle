<?php

namespace Smirik\CourseBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use Smirik\QuizBundle\Model\QuizQuery;
use Smirik\QuizBundle\Model\Quiz;
use Smirik\QuizBundle\Model\Question;
use Smirik\QuizBundle\Model\Answer;

class ImportCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('lms42:import:course')
            ->setDescription('Import course data')
            ->addOption('file', null, InputOption::VALUE_REQUIRED, 'Full path to file')
            ->addOption('dry-run', null, InputOption::VALUE_NONE, 'Do not add to database')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $file    = $input->getOption('file');
        $dry_run = $input->getOption('dry-run', false);

        $file = realpath($this->getContainer()->get('kernel')->getRootDir().'/../'.$file);
        
        if (!file_exists($file)) {
            $output->writeln('<error>File not found!</error>');
            return;
        }
        
        $data = file_get_contents($file);
        $res = $this->getContainer()->get('course.importer')->import($data, $dry_run);
        $output->writeln('<info>All data were added.</info>');

    }
}
