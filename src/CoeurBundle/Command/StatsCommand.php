<?php

namespace CoeurBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use CoeurBundle\Entity\Stats;

class StatsCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('coeurstats:full')
            ->setDescription('Mettre les stats à 100% d\'un utilisateur')
            ->addArgument('name', InputArgument::OPTIONAL, 'Indiquer l\'id de l\'utilisateur')
            ->addOption(1, null, InputOption::VALUE_NONE, 'Oh, c\'est pour l\'admin !')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $name = $input->getArgument('name');
        if ($name) {
            $em = $this->getContainer()->get('doctrine.orm.entity_manager');
            $stats = $em->getRepository('CoeurBundle:Stats')->findOneByUtilisateurs($name); // On récupère les stats

            $stats->setVie('100');
            $stats->setFaim('100');
            $stats->setSoif('100');
            $stats->setFatigue('100');
            $stats->setTemperature('37');

            $em->flush();

            $text = "Tout est ok !";

        } else {
            $text = 'L\'utilisateur n\'existe pas.';
        }

        if ($input->getOption(1)) {

            $text .= "Hey le patron, tout est ok !";
        }

        $output->writeln($text);
    }
}