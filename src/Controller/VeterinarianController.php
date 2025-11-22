<?php

    namespace App\Controller;

    use App\Entity\Veterinarian;
    use App\Form\VeterinarianType;
    use App\Repository\VeterinarianRepository;
    use Doctrine\ORM\EntityManagerInterface;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Attribute\Route;

    class VeterinarianController extends AbstractController{

        #[Route('/veterinario', name: 'veterinarian_index')]
        public function index(VeterinarianRepository $veterinarianRepository): Response
        {
            $data = [
                        'title' => 'Gerenciar Veterinários',
                        'veterinarians' => $veterinarianRepository->findAll()
            ];

            return $this->render('veterinarian/index.html.twig', $data);
        }


        #[Route('/veterinario/adicionar', name: 'veterinarian_add')]
        public function addVeterinarian(Request $request, EntityManagerInterface $em) : Response
        {
            $veterinarian = new Veterinarian;
            $form = $this->createForm(VeterinarianType::class, $veterinarian);
            $form->handleRequest($request);

            if($form->isSubmitted() && $form->isValid()){

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
                'form'  => $form,
            ]);
        }


        #[Route('/veterinario/editar/{id}', name: 'veterinarian_edit')]
        public function editVeterinarian($id, Request $request, EntityManagerInterface $em, VeterinarianRepository $veterinarianRepository) : Response
        {
            $veterinarian = $veterinarianRepository->find($id);
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

            $data = [
                        'title' => 'Editar Veterinário',
                        'form'  => $form
            ];

            return $this->render('veterinarian/form.html.twig', $data);
        }

        #[Route('/veterinario/apagar/{id}', name: 'veterinarian_remove')]
        public function removeVeterinarian($id, EntityManagerInterface $em, VeterinarianRepository $veterinarianRepository) : Response
        {
            $veterinarian = $veterinarianRepository->find($id);
            $em->remove($veterinarian);
            $em->flush();
            $this->addFlash('success', 'Veterinário removido com sucesso!');

            return $this->redirectToRoute('veterinarian_index');
        }
    }