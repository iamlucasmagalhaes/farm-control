<?php

    namespace App\Controller;

    use App\Entity\Veterinarian;
    use Doctrine\DBAL\Exception;
    use Doctrine\ORM\EntityManagerInterface;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Attribute\Route;

    class VeterinarianController extends AbstractController{

            #[Route('/veterinario', name: 'veterinarian_index')]
            public function index(EntityManagerInterface $em): Response
            {
                $veterinarian = new Veterinarian();
                $veterinarian->setName("Lucas");
                $veterinarian->setCrmv("1");
                $msg = "";

                try{
                    $em->persist($veterinarian);
                    $em->flush();
                    $msg = "Veterinário cadastrado com sucesso";
                } catch (Exception $e){
                    $msg = "Erro ao cadastrar veterinário";
                }
                return new Response("<h1> $msg </h1>");
            }
    }