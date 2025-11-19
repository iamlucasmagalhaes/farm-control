<?php

    namespace App\Controller;

    use App\Entity\Cow;
    use Doctrine\DBAL\Exception;
    use App\Repository\FarmRepository;
    use Doctrine\ORM\EntityManagerInterface;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Attribute\Route;

    class CowController extends AbstractController{
        
        #[Route('/gado', name: 'gado_index')]
        public function index(EntityManagerInterface $em, FarmRepository $farmRepository) : Response{
            $farm = $farmRepository->findOneBy([
                'name' => 'Recanto da Lua'
            ]);

            $cow = new Cow();
            $cow->setCode("COW-001");
            $cow->setMilkperweek(45.5);
            $cow->setFoodperweek(32.8);
            $cow->setWeight(530.2);
            $cow->setBirthdate(new \DateTime("2019-04-10"));            
            $cow->setFarm($farm); // Fazenda associada (objeto Farm já buscado no banco)
            $cow->setIsslaughtered(false);
            $cow->setSlaughterdate(null);
            $cow->setIsalive(true);

            // Datas de criação e atualização
            $cow->setCreatedat(new \DateTime("now"));
            $cow->setUpdatedat(new \DateTime("now"));
            $msg = "";

            try{
                $em->persist($cow);
                $em->flush();
                $msg = "Gado cadastrado com sucesso";
            } catch (Exception $e){
                $msg = "Erro ao cadastrar gado";
            }
            return new Response("<h1> $msg </h1>");
        }
    }