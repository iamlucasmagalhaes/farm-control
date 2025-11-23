<?php

namespace App\Controller;

use App\Entity\Cow;
use App\Form\CowType;
use App\Repository\CowRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CowController extends AbstractController
{
    #[Route('/gado', name: 'cow_index')]
    public function index(CowRepository $repo, PaginatorInterface $paginator, Request $request): Response
    {
        $qb = $repo->createQueryBuilder('c')
            ->leftJoin('c.farm', 'f')
            ->addSelect('f');

        $pagination = $paginator->paginate(
            $qb->getQuery(),
            $request->query->getInt('page', 1),
            10,
            [
                'defaultSortFieldName' => 'c.id',
                'defaultSortDirection' => 'asc',
            ]
        );

        return $this->render('cow/index.html.twig', [
            'title' => 'Gerenciar Gados',
            'pagination' => $pagination,
        ]);
    }

    #[Route('/gado/adicionar', name: 'cow_add')]
    public function addCow(Request $request, EntityManagerInterface $em, CowRepository $cowRepository): Response
    {
        $cow = new Cow();
        $form = $this->createForm(CowType::class, $cow);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $farm = $cow->getFarm();
            $maxAnimals = $farm->getSize() * 18;
            $currentAnimals = $farm->getCows()->filter(fn($c) => $c->isAlive())->count();

            if ($currentAnimals >= $maxAnimals) {
                $this->addFlash('danger', 'Esta fazenda atingiu o limite máximo de animais permitidos.');
                return $this->redirectToRoute('cow_add');
            }

            $existing = $cowRepository->findOneBy([
                'code' => $cow->getCode(),
                'isalive' => true
            ]);

            if ($existing) {
                $this->addFlash('danger', 'Já existe um animal vivo com este código.');
                return $this->redirectToRoute('cow_add');
            }

            try {
                $em->persist($cow);
                $em->flush();

                $this->addFlash('success', 'Gado adicionado com sucesso!');
                return $this->redirectToRoute('cow_index');
            } catch (\Doctrine\DBAL\Exception\UniqueConstraintViolationException $e) {
                $this->addFlash('danger', 'Erro: já existe um gado com este código.');
            }
        }

        return $this->render('cow/form.html.twig', [
            'title' => 'Adicionar Gado',
            'form' => $form,
        ]);
    }

    #[Route('/gado/editar/{id}', name: 'cow_edit')]
    public function editCow(int $id, Request $request, EntityManagerInterface $em, CowRepository $cowRepository): Response
    {
        $cow = $cowRepository->find($id);
        if (!$cow) {
            $this->addFlash('danger', 'Gado não encontrado.');
            return $this->redirectToRoute('cow_index');
        }

        $form = $this->createForm(CowType::class, $cow);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $farm = $cow->getFarm();
            $maxAnimals = $farm->getSize() * 18;

            $currentAnimals = $farm->getCows()->filter(fn ($c) => $c->isAlive())->count();

            // Se o animal sendo editado estiver vivo, subtrai ele para avaliar a mudança de fazenda
            if ($cow->isAlive()) {
                $currentAnimals--;
            }

            if ($currentAnimals >= $maxAnimals && $cow->isAlive()) {
                $this->addFlash('danger', 'Esta fazenda atingiu o limite máximo de animais permitidos.');
                return $this->redirectToRoute('cow_edit', ['id' => $cow->getId()]);
            }

            $existing = $cowRepository->findOneBy([
                'code' => $cow->getCode(),
                'isalive' => true
            ]);

            if ($existing && $existing->getId() !== $cow->getId()) {
                $this->addFlash('danger', 'Já existe outro animal vivo com este código.');
                return $this->redirectToRoute('cow_edit', ['id' => $cow->getId()]);
            }

            try {
                $cow->setUpdatedat(new \DateTime());
                $em->flush();

                $this->addFlash('success', 'Gado atualizado com sucesso!');
                return $this->redirectToRoute('cow_index');
            } catch (\Doctrine\DBAL\Exception\UniqueConstraintViolationException $e) {
                $this->addFlash('danger', 'Erro: já existe um gado com este código.');
            }
        }

        return $this->render('cow/form.html.twig', [
            'title' => 'Editar Gado',
            'form' => $form,
        ]);
    }

    #[Route('/gado/apagar/{id}', name: 'cow_remove')]
    public function removeCow(int $id, EntityManagerInterface $em, CowRepository $cowRepository): Response
    {
        $cow = $cowRepository->find($id);
        if (!$cow) {
            $this->addFlash('danger', 'Gado não encontrado.');
            return $this->redirectToRoute('cow_index');
        }

        try {
            $em->remove($cow);
            $em->flush();
            $this->addFlash('success', 'Gado removido com sucesso!');
        } catch (\Exception $e) {
            $this->addFlash('danger', 'Erro ao remover o gado.');
        }

        return $this->redirectToRoute('cow_index');
    }

    #[Route('/gado/abater/{id}', name: 'cow_slaughter')]
    public function slaughter(int $id, CowRepository $repo, EntityManagerInterface $em): Response
    {
        $cow = $repo->find($id);
        if (!$cow) {
            $this->addFlash('danger', 'Gado não encontrado.');
            return $this->redirectToRoute('cow_index');
        }

        if (!method_exists($cow, 'canBeSlaughtered') || !$cow->canBeSlaughtered()) {
            $this->addFlash('danger', 'Este animal não atende os requisitos para abate.');
            return $this->redirectToRoute('cow_index');
        }

        $cow->setIsalive(false);
        $cow->setIsslaughtered(true);
        $cow->setSlaughterdate(new \DateTime());
        $cow->setUpdatedat(new \DateTime());

        $em->flush();

        $this->addFlash('success', 'Animal abatido com sucesso.');
        return $this->redirectToRoute('cow_index');
    }
}
