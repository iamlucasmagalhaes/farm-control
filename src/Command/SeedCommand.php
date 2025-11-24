<?php

    namespace App\Command;

    use App\Entity\Cow;
    use App\Entity\Farm;
    use App\Entity\Veterinarian;
    use Doctrine\ORM\EntityManagerInterface;
    use Symfony\Component\Console\Attribute\AsCommand;
    use Symfony\Component\Console\Command\Command;
    use Symfony\Component\Console\Input\InputInterface;
    use Symfony\Component\Console\Output\OutputInterface;

    #[AsCommand(
        name: 'app:seed',
        description: 'Popula o banco com veterinários, fazendas e gado (dados válidos).'
    )]
    class SeedCommand extends Command
    {
        public function __construct(private EntityManagerInterface $em)
        {
            parent::__construct();
        }

        protected function execute(InputInterface $input, OutputInterface $output): int
        {
            $output->writeln('<info>Iniciando seed...</info>');

            $vetsData = [
                ['Ana Souza', 'CRMV-101'],
                ['Carlos Ribeiro', 'CRMV-102'],
                ['Mariana Silva', 'CRMV-103'],
                ['Fernando Costa', 'CRMV-104'],
                ['Lucas Pereira', 'CRMV-105'],
                ['Beatriz Gomes', 'CRMV-106'],
                ['Rafael Lima', 'CRMV-107'],
                ['Paula Mendes', 'CRMV-108'],
                ['Eduardo Rocha', 'CRMV-109'],
                ['Camila Alves', 'CRMV-110'],
            ];

            $veterinarians = [];
            foreach ($vetsData as [$name, $crmv]) {
                $existing = $this->em->getRepository(Veterinarian::class)->findOneBy(['crmv' => $crmv]);
                if ($existing) {
                    $veterinarians[] = $existing;
                    continue;
                }

                $vet = new Veterinarian();
                $vet->setName($name);
                $vet->setCrmv($crmv);

                $this->em->persist($vet);
                $veterinarians[] = $vet;
            }

            $farmsData = [
                ['Fazenda Estrela', 10.0],
                ['Fazenda Santa Luzia', 5.0],
                ['AgroCampo Verde', 3.0],
            ];

            $farms = [];
            foreach ($farmsData as [$name, $hectares]) {
                $existing = $this->em->getRepository(Farm::class)->findOneBy(['name' => $name]);
                if ($existing) {
                    $farms[] = $existing;
                    continue;
                }

                $farm = new Farm();
                $farm->setName($name);
                $farm->setSize(number_format($hectares, 2, '.', ''));
                $farm->setResponsible("Responsável {$name}");

                $vetA = $veterinarians[array_rand($veterinarians)];
                $vetA->addFarm($farm);
                $vetB = $veterinarians[array_rand($veterinarians)];
                if ($vetB !== $vetA) {
                    $vetB->addFarm($farm);
                }

                $this->em->persist($farm);
                $farms[] = $farm;
            }

            $this->em->flush();

            $output->writeln('Criando gado (respeitando 18 animais/ha, populando ~50% da capacidade)...');

            $cowCounter = 1;
            foreach ($farms as $farm) {
                $size = (float) $farm->getSize();
                $capacity = (int) floor($size * 18);
                $numToCreate = max(1, (int) floor($capacity * 0.5));

                for ($i = 0; $i < $numToCreate; $i++) {
                    do {
                        $code = sprintf('COW-%04d', $cowCounter++);
                        $exists = $this->em->getRepository(Cow::class)->findOneBy(['code' => $code]);
                    } while ($exists);

                    $cow = new Cow();
                    $cow->setCode($code);

                    $years = rand(1, 10);
                    $months = (int) round($years * 12);
                    $birthdate = (new \DateTime())->modify("-{$months} months");
                    $cow->setBirthdate($birthdate);

                    $cow->setMilkperweek((float) rand(20, 100));

                    $cow->setFoodperweek((float) rand(140, 490));

                    $cow->setWeight((float) rand(300, 700));

                    $cow->setIsalive(true);
                    $cow->setIsslaughtered(false);
                    $cow->setSlaughterdate(null);
                    $now = new \DateTime();
                    $cow->setCreatedat($now);
                    $cow->setUpdatedat($now);

                    $cow->setFarm($farm);

                    $this->em->persist($cow);
                }
            }

            $output->writeln('Adicionando animais com ≤1 ano e >500 kg/semana de ração...');

            $specialAnimals = [
                ['BEB-001', 0.8, 10.0, 520.0, 100.0],
                ['BEB-002', 0.6, 8.0, 650.0, 120.0],
                ['BEB-003', 1.0, 12.0, 700.0, 110.0],
            ];

            foreach ($specialAnimals as [$code, $ageYears, $milk, $foodPerWeek, $weight]) {
                $exists = $this->em->getRepository(Cow::class)->findOneBy(['code' => $code]);
                if ($exists) {
                    continue;
                }

                $cow = new Cow();
                $cow->setCode($code);

                $months = (int) round($ageYears * 12);
                $cow->setBirthdate((new \DateTime())->modify("-{$months} months"));

                $cow->setMilkperweek($milk);
                $cow->setFoodperweek($foodPerWeek);
                $cow->setWeight($weight);

                $cow->setIsalive(true);
                $cow->setIsslaughtered(false);
                $cow->setSlaughterdate(null);
                $cow->setCreatedat(new \DateTime());
                $cow->setUpdatedat(new \DateTime());

                $cow->setFarm($farms[0]);

                $this->em->persist($cow);
            }

            $output->writeln('Adicionando animais que NÃO serão abatidos (para validação)...');

            $safeAnimals = [
                ['SAFE-001', 2.0, 90.0, 200.0, 250.0],
                ['SAFE-002', 3.5, 110.0, 150.0, 260.0],
                ['SAFE-003', 4.0, 95.0, 180.0, 240.0],
            ];

            foreach ($safeAnimals as [$code, $ageYears, $milk, $foodPerWeek, $weight]) {
                $exists = $this->em->getRepository(Cow::class)->findOneBy(['code' => $code]);
                if ($exists) {
                    continue;
                }

                $cow = new Cow();
                $cow->setCode($code);

                $months = (int) round($ageYears * 12);
                $cow->setBirthdate((new \DateTime())->modify("-{$months} months"));

                $cow->setMilkperweek($milk);
                $cow->setFoodperweek($foodPerWeek);
                $cow->setWeight($weight);

                $cow->setIsalive(true);
                $cow->setIsslaughtered(false);
                $cow->setSlaughterdate(null);
                $cow->setCreatedat(new \DateTime());
                $cow->setUpdatedat(new \DateTime());

                $cow->setFarm($farms[1]);

                $this->em->persist($cow);
            }

            $this->em->flush();

            $output->writeln('<info>Seed concluído com sucesso.</info>');

            return Command::SUCCESS;
        }
    }
