<?php

    namespace App\Controller;

    use App\Entity\Farm;
    use App\Form\FarmType;
    use App\Repository\FarmRepository;
    use Doctrine\ORM\EntityManagerInterface;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Attribute\Route;

    class FarmController extends AbstractController{

        #[Route('/fazenda', name: 'farm_index')]
        public function index(FarmRepository $farmRepository): Response
        {
            $data = [
                        'title' => 'Gerenciar Fazendas',
                        'farms' => $farmRepository->findAll()
            ];

            return $this->render('farm/index.html.twig', $data);
        }

         #[Route('/fazenda/adicionar', name: 'farm_add')]
         public function addFarm(Request $request, EntityManagerInterface $em) : Response
         {
            $msg = '';
            $farm = new Farm;
            $form = $this->createForm(FarmType::class, $farm);
            $form->handleRequest($request);

            if($form->isSubmitted() && $form->isValid()){
                $em->persist($farm);
                $em->flush();
                $msg ='Fazenda adicionada com sucesso!';
            }

            $data = [
                            'title' => 'Adicionar Fazenda',
                            'form'  => $form,
                            'msg' => $msg,
            ];

            return $this->render('farm/form.html.twig', $data);
         }
    }