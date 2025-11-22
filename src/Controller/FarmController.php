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
        public function addFarm(Request $request, EntityManagerInterface $em): Response
        {
            $farm = new Farm;
            $form = $this->createForm(FarmType::class, $farm);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {

                try {
                    $em->persist($farm);
                    $em->flush();

                    $this->addFlash('success', 'Fazenda adicionada com sucesso!');
                    return $this->redirectToRoute('farm_index');

                } catch (\Doctrine\DBAL\Exception\UniqueConstraintViolationException $e) {
                    $this->addFlash('danger', 'Erro: este nome de fazenda já está cadastrado.');
                }
            }

            return $this->render('farm/form.html.twig', [
                'title' => 'Adicionar Fazenda',
                'form'  => $form
            ]);
        }


        #[Route('/fazenda/editar/{id}', name: 'farm_edit')]
        public function editFarm($id, Request $request, EntityManagerInterface $em, FarmRepository $farmRepository) : Response
        {
            $farm = $farmRepository->find($id);
            $form = $this->createForm(FarmType::class, $farm);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                try {
                    $em->flush();
                    $this->addFlash('success', 'Fazenda atualizada com sucesso!');
                    return $this->redirectToRoute('farm_index');

                } catch (\Doctrine\DBAL\Exception\UniqueConstraintViolationException $e) {
                    $this->addFlash('danger', 'Erro: já existe uma fazenda com este nome.');
                }
            }


            $data = [
                        'title' => 'Editar Fazenda',
                        'form'  => $form
            ];

            return $this->render('farm/form.html.twig', $data);
        }

        #[Route('/fazenda/apagar/{id}', name: 'farm_remove')]
        public function removeFarm($id, EntityManagerInterface $em, FarmRepository $farmRepository) : Response
        {
            $farm = $farmRepository->find($id);

            if (!$farm) {
                $this->addFlash('danger', 'Fazenda não encontrada.');
                return $this->redirectToRoute('farm_index');
            }

            if ($farm->getCows()->count() > 0) {
                $this->addFlash('danger', 'Não é possível excluir: a fazenda possui gado associado.');
                return $this->redirectToRoute('farm_index');
            }

            $em->remove($farm);
            $em->flush();

            $this->addFlash('success', 'Fazenda apagada com sucesso!');
            return $this->redirectToRoute('farm_index');
        }

    }