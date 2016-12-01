<?php

namespace Sunday\CommandBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Goutte\Client;
use Html2Text;

class SundayQuestionBotCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('sunday:question-bot')
            ->setDescription('...')
            ->addArgument('argument', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('baidu-hot', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $argument = $input->getArgument('argument');

        if ($input->getOption('baidu-hot')) {
            $client = new Client();
            $baiduHotURL = 'http://top.baidu.com/buzz?b=1&fr=topindex';
            $crawler = $client->request('GET', $baiduHotURL);
            $lastNumber = (integer) $crawler->filter('.num-normal')->last()->text() - 1;
            $randomNodeNumber = rand(0, $lastNumber);
            dump($randomNodeNumber);
            $keyWords = $crawler->filter('.keyword .list-title')->eq($randomNodeNumber)->text();
            $text = Html2Text\Html2Text::convert($keyWords);
            $output->writeln(sprintf('你是如何看待%s的', $text));
        }
    }

}
