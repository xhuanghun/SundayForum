<?php

namespace Sunday\CommandBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Fukuball\Jieba\Jieba;
use Fukuball\Jieba\Finalseg;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;
use Goutte\Client;
use Html2Text;


class SundayIgorCommand extends ContainerAwareCommand
{
    protected $commandDirectory;
    protected $commandLockFile;
    protected $fs;
    protected $output;
    protected $finder;
    protected $em;
    protected $language;

    protected function configure()
    {
        $this
            ->setName('sunday:igor')
            ->setDescription('...')
            ->addArgument('argument', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @variable $dicPath The path of your customize dictionary.
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        ini_set('memory_limit', '1024M');

        $this->output = $output;
        $rootDir = $this->getContainer()->get('kernel')->getRootDir();
        $pusher = $this->getContainer()->get('gos_web_socket.wamp.pusher');
        $dicPath = $rootDir.'/../vendor/fukuball/jieba-php/src/dict/dict.test.txt';

        Jieba::init();
        Jieba::loadUserDict($dicPath);
        Finalseg::init();

        $argument = $input->getArgument('argument');

        if ($input->getOption('option')) {
            // ...
        }

        $this->commandDirectory = $rootDir.'/../app/cache/command';
        $this->em = $this->getContainer()->get('doctrine')->getManager();
        $this->commandLockFile = $this->commandDirectory."/lock/__commandlock.file";

        $this->fs = new Filesystem();

        if (!$this->fs->exists($this->commandDirectory)) {
            $output->writeln("Command directory is not exit");
        }

        $sortByCreatedTime = function (\SplFileInfo $a, \SplFileInfo $b)
        {
            return strcmp($a->getATime(), $b->getATime());
        };

        $this->language = new ExpressionLanguage();
        $this->language->register('谁是', function ($str) {
            return $str;
        }, function ($arguments, $str) {
            $client = new Client();
            $baiduWikiURL = 'http://baike.baidu.com/';
            $crawler = $client->request('GET', $baiduWikiURL);
            $form = $crawler->selectButton('进入词条')->form();
            $crawler = $client->submit($form, ['word' => $str]);
            $htmlContent = $crawler->filter('.lemma-summary')->first()->text();
            $text = Html2Text\Html2Text::convert($htmlContent);
            $pattern = '/\[([1-9][0-9]*)\]/';
            $text = preg_replace($pattern, '', $text);
            return $text;
        });

        while (true) {
            if ($this->checkLockFile()) {
                $this->finder = new Finder();
                $this->finder->files()->in($this->commandDirectory)->exclude('lock')->sort($sortByCreatedTime);
                foreach ($this->finder as $file) {
                    $this->output->writeln($file->getRelativePathname());

                    $command = $this->em->getRepository('SundayCommandBundle:Command')
                        ->findOneBy(['token' => $file->getRelativePathname()]);

                    $commandContent = $command->getContent();
                    $user = $command->getUser();

                    $segList = Jieba::cut($commandContent);

                    $name = implode(array_slice($segList, 0, -2));
                    $function = array_slice($segList, -1, 1)[0] . array_slice($segList, -2, 1)[0];
                    $expressionResult = $this->language->evaluate("$function('$name')");

                    if ($user) {
                        $pusher->push(['data' => $expressionResult, 'username' => 'tony'], 'igor_topic', []);
                    }

                    $this->output->writeln($expressionResult);

                    $command->setChecked(true);
                    $this->em->persist($command);
                }

                $this->em->flush();

                $datetime = New \DateTime();
                $this->output->writeln("----------".$datetime->format("Y.m.d-H:m:s")."----------");
            } else {
                $this->output->writeln("Lock file is not exit");
            }

            empty($this->finder);
            sleep(1);
        }
    }

    protected function checkLockFile() {
        return $this->fs->exists($this->commandLockFile);
    }

}
