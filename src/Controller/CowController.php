<?php

    namespace App\Controller;

    use App\Entity\Cow;
    use App\Form\CowType;
    use App\Repository\CowRepository;
    use Doctrine\ORM\EntityManagerInterface;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Attribute\Route;

    class CowController extends AbstractController{
        
        #[Route('/gado', name: 'cow_index')]
        public function index(CowRepository $cowRepository) : Response
        {
            $data = [
                        'title' => 'Gerenciar Gados',
                        'cows' => $cowRepository->findAll()
            ];
            
            return $this->render('cow/index.html.twig', $data);
        }

        #[Route('/gado/adicionar', name: 'cow_add')]
        public function addCow(Request $request, EntityManagerInterface $em) : Response
        {
            $msg = '';
            $cow = new Cow;
            $form = $this->createForm(CowType::class, $cow);
            $form->handleRequest($request);

            if($form->isSubmitted() && $form->isValid()){
                $em->persist($cow);
                $em->flush();
                $msg ='Gado adicionado com sucesso!';
            }

            $data = [
                        'title' => 'Adicionar Gado',
                        'form'  => $form,
                        'msg' => $msg,
            ];

            return $this->render('cow/form.html.twig', $data);
        }
    }