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

        #[Route('/fazenda/editar/{id}', name: 'farm_edit')]
        public function editFarm($id, Request $request, EntityManagerInterface $em, FarmRepository $farmRepository) : Response
        {
            $msg = '';
            $farm = $farmRepository->find($id);
            $form = $this->createForm(FarmType::class, $farm);
            $form->handleRequest($request);

            if($form->isSubmitted() && $form->isValid()){
                $em->flush();
                $msg = 'Fazenda Atualizado com sucesso!';
            }

            $data = [
                        'title' => 'Editar Fazenda',
                        'form'  => $form,
                        'msg' => $msg,
            ];

            return $this->render('farm/form.html.twig', $data);
        }

        #[Route('/fazenda/apagar/{id}', name: 'farm_remove')]
        public function removeFarm($id, EntityManagerInterface $em, FarmRepository $farmRepository) : Response
        {
            $farm = $farmRepository->find($id);
            $em->remove($farm);
            $em->flush();

            return $this->redirectToRoute('farm_index');
        }
    }