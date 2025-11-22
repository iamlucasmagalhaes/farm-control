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
            $cow = new Cow;
            $form = $this->createForm(CowType::class, $cow);
            $form->handleRequest($request);

            if($form->isSubmitted() && $form->isValid()){
                try {
                    $em->persist($cow);
                    $em->flush();

                    $this->addFlash('success', 'Gado adicionado com sucesso!');
                    return $this->redirectToRoute('cow_index');

                } catch (\Doctrine\DBAL\Exception\UniqueConstraintViolationException $e) {
                    $this->addFlash('danger', 'Erro: já existe um gado com este código.');
                }

            }

            $data = [
                        'title' => 'Adicionar Gado',
                        'form'  => $form
            ];

            return $this->render('cow/form.html.twig', $data);
        }

        #[Route('/gado/editar/{id}', name: 'cow_edit')]
        public function editCow($id, Request $request, EntityManagerInterface $em, CowRepository $cowRepository) : Response
        {
            $cow = $cowRepository->find($id);
            $form = $this->createForm(CowType::class, $cow);
            $form->handleRequest($request);

            if($form->isSubmitted() && $form->isValid()){
                try {
                    $em->flush();
                    $this->addFlash('success', 'Gado atualizado com sucesso!');
                    return $this->redirectToRoute('cow_index');

                } catch (\Doctrine\DBAL\Exception\UniqueConstraintViolationException $e) {
                    $this->addFlash('danger', 'Erro: já existe um gado com este código.');
                }

            }

            $data = [
                        'title' => 'Editar Gado',
                        'form'  => $form
            ];

            return $this->render('cow/form.html.twig', $data);
        }

        #[Route('/gado/apagar/{id}', name: 'cow_remove')]
        public function removeCow($id, EntityManagerInterface $em, CowRepository $cowRepository): Response
        {
            $cow = $cowRepository->find($id);

            try {
                $em->remove($cow);
                $em->flush();

                $this->addFlash('success', 'Gado removido com sucesso!');
            } catch (\Exception $e) {
                $this->addFlash('danger', 'Erro ao remover o gado.');
            }

            return $this->redirectToRoute('cow_index');
        }

    }