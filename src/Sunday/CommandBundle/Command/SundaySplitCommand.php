<?php

namespace Sunday\CommandBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Fukuball\Jieba\Jieba;
use Fukuball\Jieba\Finalseg;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;
use Goutte\Client;
use Html2Text;

class SundaySplitCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('sunday:split')
            ->setDescription('...')
            ->addArgument('argument', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        ini_set('memory_limit', '1024M');
        Jieba::init();
        Jieba::loadUserDict("/Users/tony/Sites/SundayForum/SundayForum/vendor/fukuball/jieba-php/src/dict/dict.test.txt");
        Finalseg::init();

        if ($input->getOption('option')) {

        }

        $language = new ExpressionLanguage();
        $language->register('谁是', function ($str) {
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

        $argument = $input->getArgument('argument');

        $seg_list = Jieba::cut($argument);

        $name = implode(array_slice($seg_list, 0, -2));
        $function = array_slice($seg_list, -1, 1)[0] . array_slice($seg_list, -2, 1)[0];
        $expressionResult = $language->evaluate("$function('$name')");
        $output->writeln($expressionResult);
    }

}
