<?php

namespace Sunday\CommandBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;
use Symfony\Component\Console\Style\SymfonyStyle;

class SundayMusicCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('sunday:music')
            ->setDescription('...')
            ->addArgument('argument', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $argument = $input->getArgument('argument');
        $style = new OutputFormatterStyle('red', 'yellow', array('bold', 'blink'));
        $output->getFormatter()->setStyle('fire', $style);

        if ($input->getOption('option')) {
            // ...
        }

        $finder = new Finder();
        $finder->files()->in("/Users/tony/music/testMusic");
        $finder->files()->name('*.mp3');
        $number = 0;
        $numberArray = [];
        $rowsArray = [];
        $fileObjectArray = [];
        $fileNameArray = [];

        foreach ($finder as $file) {
            /** Fucking error, fucking unknown charset encoding, maybe use id3 in next version, I don't know :(
            $tag = @id3_get_tag($file->getRealPath());
            foreach (array_keys($tag) as $key) {
                $tag[$key] = iconv('gb2312', 'UTF-8//IGNORE', $tag[$key]);
                $tag[$key] = mb_detect_encoding($tag[$key]);
            } **/

            $numberArray[] = $number++;
            $fileName = $file->getFilename();
            $fileNameArray[] = $fileName;
            $fileSize = $this->getFileSize($file->getSize());
            $rowArray = [$number, $fileName, $fileSize];
            $rowsArray[] = $rowArray;
            $fileObjectArray[$number] = $file;
        }

        $table = new Table($output);
        $table
            ->setHeaders(['No.', 'File Name', 'File Size'])
            ->setRows($rowsArray)
        ;
        $table->render();

        $helper = $this->getHelper('question');
        $questionNumber = new Question('Please enter your begin music number: ');
        $questionNumber->setAutocompleterValues($numberArray);
        $musicNumber = $helper->ask($input, $output, $questionNumber);
        $io->success('Enjoy the music :D');

        // choice by file name? Not sure, maybe you like it -_-|
        //$question = new Question('Please enter your music file name', 'file name');
        //$question->setAutocompleterValues($fileNameArray);
        //$musicName = $helper->ask($input, $output, $question);

        while (true) {
            $chosenFile = $fileObjectArray[$musicNumber];
            $chosenFileName = $chosenFile->getFilename();
            $chosenFileSize = $this->getFileSize($chosenFile->getSize());
            $pathToMusic = preg_replace('/\s+/', '\\ ', $chosenFile->getRealPath());
            $io->table(
                ["序号", "文件名", "文件大小"],
                [[$musicNumber, $chosenFileName, $chosenFileSize]]
            );
            $this->playMusic($pathToMusic);
            $musicNumber == $number ? $musicNumber = 1 : $musicNumber++ ;
        }
    }

    protected function playMusic($path) {
        $process = new Process("afplay $path");
        $process->setTimeout(null);
        $process->run();
    }

    protected function getFileSize($size)
    {
        if($size < 1024) {
            return $size . "B";
        } else {
            $help = $size / 1024;
            if($help < 1024) {
                return round($help, 1) . "KB";
            } else {
                return round(($help / 1024), 1) . "MB";
            }
        }
    }
}
