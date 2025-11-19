<?php

    namespace App\Controller;

    use App\Entity\Farm;
    use Doctrine\DBAL\Exception;
    use App\Repository\VeterinarianRepository;
    use Doctrine\ORM\EntityManagerInterface;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Attribute\Route;

    class FarmController extends AbstractController{

        #[Route('/fazenda', name: 'farm_index')]
        public function index(EntityManagerInterface $em, VeterinarianRepository $veterinarianRepository): Response
        {
            $veterinarian = $veterinarianRepository->findOneBy([
                'crmv' => '1',
            ]);
            $farm = new Farm();

            $farm->setName("Recanto da Lua");
            $farm->setSize(2);
            $farm->setResponsible("Lavínia");
            $farm->addVeterinarian($veterinarian);

            $msg = "";

            try{
                $em->persist($farm);
                $em->flush();
                $msg = "Fazenda cadastrada com sucesso";
            } catch (Exception $e){
                $msg = "Erro ao cadastrar fazenda";
            }
            return new Response("<h1> $msg </h1>");
        }
    }