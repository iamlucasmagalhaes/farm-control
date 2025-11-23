<?php

namespace App\Controller;

use App\Entity\Farm;
use App\Form\FarmType;
use App\Repository\FarmRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FarmController extends AbstractController
{
    #[Route('/fazenda', name: 'farm_index')]
    public function index(FarmRepository $repo, PaginatorInterface $paginator, Request $request): Response
    {
        $qb = $repo->createQueryBuilder('f');

        $pagination = $paginator->paginate(
            $qb->getQuery(),
            $request->query->getInt('page', 1),
            10,
            [
                'defaultSortFieldName' => 'f.id',
                'defaultSortDirection' => 'asc',
            ]
        );

        return $this->render('farm/index.html.twig', [
            'title' => 'Gerenciar Fazendas',
            'pagination' => $pagination,
        ]);
    }

    #[Route('/fazenda/adicionar', name: 'farm_add')]
    public function addFarm(Request $request, EntityManagerInterface $em): Response
    {
        $farm = new Farm();
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
            'form' => $form,
        ]);
    }

    #[Route('/fazenda/editar/{id}', name: 'farm_edit')]
    public function editFarm(int $id, Request $request, EntityManagerInterface $em, FarmRepository $farmRepository): Response
    {
        $farm = $farmRepository->find($id);
        if (!$farm) {
            $this->addFlash('danger', 'Fazenda não encontrada.');
            return $this->redirectToRoute('farm_index');
        }

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

        return $this->render('farm/form.html.twig', [
            'title' => 'Editar Fazenda',
            'form' => $form,
        ]);
    }

    #[Route('/fazenda/apagar/{id}', name: 'farm_remove')]
    public function removeFarm(int $id, EntityManagerInterface $em, FarmRepository $farmRepository): Response
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

        try {
            $em->remove($farm);
            $em->flush();
            $this->addFlash('success', 'Fazenda apagada com sucesso!');
        } catch (\Exception $e) {
            $this->addFlash('danger', 'Erro ao apagar a fazenda.');
        }

        return $this->redirectToRoute('farm_index');
    }
}
