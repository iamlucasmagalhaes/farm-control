<?php

    namespace App\Controller;

    use App\Entity\Veterinarian;
    use App\Form\VeterinarianType;
    use App\Repository\VeterinarianRepository;
    use Doctrine\ORM\EntityManagerInterface;
    use Knp\Component\Pager\PaginatorInterface;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Annotation\Route;

    class VeterinarianController extends AbstractController
    {
        #[Route('/veterinario', name: 'veterinarian_index')]
        public function index(VeterinarianRepository $repo, PaginatorInterface $paginator, Request $request): Response
        {
            $qb = $repo->createQueryBuilder('v');

            $pagination = $paginator->paginate(
                $qb->getQuery(),
                $request->query->getInt('page', 1),
                10,
                [
                    'defaultSortFieldName' => 'v.id',
                    'defaultSortDirection' => 'asc',
                ]
            );

            return $this->render('veterinarian/index.html.twig', [
                'title' => 'Gerenciar Veterinários',
                'pagination' => $pagination,
            ]);
        }

        #[Route('/veterinario/adicionar', name: 'veterinarian_add')]
        public function addVeterinarian(Request $request, EntityManagerInterface $em): Response
        {
            $veterinarian = new Veterinarian();
            $form = $this->createForm(VeterinarianType::class, $veterinarian);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                try {
                    $em->persist($veterinarian);
                    $em->flush();

                    $this->addFlash('success', 'Profissional adicionado com sucesso!');
                    return $this->redirectToRoute('veterinarian_index');
                } catch (\Doctrine\DBAL\Exception\UniqueConstraintViolationException $e) {
                    $this->addFlash('danger', 'Erro: este CRMV já está cadastrado.');
                }
            }

            return $this->render('veterinarian/form.html.twig', [
                'title' => 'Adicionar Veterinário',
                'form' => $form,
            ]);
        }

        #[Route('/veterinario/editar/{id}', name: 'veterinarian_edit')]
        public function editVeterinarian(int $id, Request $request, EntityManagerInterface $em, VeterinarianRepository $veterinarianRepository): Response
        {
            $veterinarian = $veterinarianRepository->find($id);
            if (!$veterinarian) {
                $this->addFlash('danger', 'Veterinário não encontrado.');
                return $this->redirectToRoute('veterinarian_index');
            }

            $form = $this->createForm(VeterinarianType::class, $veterinarian);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                try {
                    $em->flush();
                    $this->addFlash('success', 'Veterinário atualizado com sucesso!');
                    return $this->redirectToRoute('veterinarian_index');
                } catch (\Doctrine\DBAL\Exception\UniqueConstraintViolationException $e) {
                    $this->addFlash('danger', 'Erro: este CRMV já está cadastrado.');
                }
            }

            return $this->render('veterinarian/form.html.twig', [
                'title' => 'Editar Veterinário',
                'form' => $form,
            ]);
        }

        #[Route('/veterinario/apagar/{id}', name: 'veterinarian_remove')]
        public function removeVeterinarian(int $id, EntityManagerInterface $em, VeterinarianRepository $veterinarianRepository): Response
        {
            $veterinarian = $veterinarianRepository->find($id);
            if (!$veterinarian) {
                $this->addFlash('danger', 'Veterinário não encontrado.');
                return $this->redirectToRoute('veterinarian_index');
            }

            try {
                $em->remove($veterinarian);
                $em->flush();
                $this->addFlash('success', 'Veterinário removido com sucesso!');
            } catch (\Exception $e) {
                $this->addFlash('danger', 'Erro ao remover o veterinário.');
            }

            return $this->redirectToRoute('veterinarian_index');
        }
    }