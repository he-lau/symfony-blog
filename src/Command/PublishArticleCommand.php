<?php

namespace App\Command;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:publish-article',
    description: 'Plublier les articles "A publier" ',
)]
class PublishArticleCommand extends Command
{

    private $articleRepository;
    private $manager;

    public function __construct(ArticleRepository $articleRepository,EntityManagerInterface $manager)
    {
        parent::__construct();
        
        $this->articleRepository = $articleRepository;
        $this->manager = $manager;
    }


    protected function configure(): void
    {
        $this
            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $articles = $this->articleRepository->findBy([
            'state'=> "a publier"
        ],[

        ]);

        foreach($articles as $article) {
            $article->setState("publie");
        }

        $this->manager->flush();

        $io->success(count($articles).' articles publiés.');

        return Command::SUCCESS;
    }
}
