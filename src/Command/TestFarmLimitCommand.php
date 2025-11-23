<?php

namespace App\Command;

use App\Entity\Cow;
use App\Repository\FarmRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:test-farm-limit',
    description: 'Testa o limite de 18 animais por hectare.',
)]
class TestFarmLimitCommand extends Command
{
    private EntityManagerInterface $em;
    private FarmRepository $farmRepository;

    public function __construct(FarmRepository $farmRepository, EntityManagerInterface $em)
    {
        parent::__construct();
        $this->em = $em;
        $this->farmRepository = $farmRepository;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $farm = $this->farmRepository->findOneBy([]);

        if (!$farm) {
            $io->error('Nenhuma fazenda encontrada. Cadastre pelo menos uma.');
            return Command::FAILURE;
        }

        $io->section("Testando limite de animais para a fazenda: {$farm->getName()}");

        $maxAnimals = $farm->getSize() * 18;
        $io->writeln("Limite da fazenda: {$maxAnimals} animais");

        $currentAnimals = $farm->getCows()->count();
        $io->writeln("Animais atuais: {$currentAnimals}");

        $animalsToAdd = ($maxAnimals - $currentAnimals) + 1;

        $io->writeln("Tentando adicionar {$animalsToAdd} animais...");

        for ($i = 1; $i <= $animalsToAdd; $i++) {
            $cow = new Cow();
            $cow->setCode('TEST' . uniqid());
            $cow->setMilkperweek(50);
            $cow->setFoodperweek(50);
            $cow->setWeight(200);
            $cow->setBirthdate(new \DateTime('2020-01-01'));
            $cow->setFarm($farm);
            $cow->setIsalive(true);
            $cow->setIsslaughtered(false);
            $cow->setCreatedat(new \DateTime());
            $cow->setUpdatedat(new \DateTime());

            $currentAnimals++;

            if ($currentAnimals > $maxAnimals) {
                $io->error("⚠️ Não foi possível adicionar o animal $i — limite alcançado!");
                return Command::SUCCESS;
            }

            $this->em->persist($cow);
        }

        $this->em->flush();

        $io->success('Todos os animais de teste foram adicionados dentro do limite permitido!');
        return Command::SUCCESS;
    }
}
