<?php

    namespace App\Controller;

    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Annotation\Route;
    use App\Repository\CowRepository;

    class ReportController extends AbstractController{
        #[Route('/fazenda/{id}/reports', name: 'farm_reports')]
        public function farmReports(int $id, CowRepository $cowRepository): Response
        {
            // Pega os animais da fazenda específica
            $slaughteredCows = $cowRepository->createQueryBuilder('c')
                ->where('c.farm = :farmId')
                ->andWhere('c.isslaughtered = :isSlaughtered')
                ->setParameter('farmId', $id)
                ->setParameter('isSlaughtered', true)
                ->orderBy('c.slaughterdate', 'DESC')
                ->getQuery()
                ->getResult();

            $totalMilk = $cowRepository->createQueryBuilder('c')
                ->select('SUM(c.milkperweek)')
                ->where('c.farm = :farmId')
                ->andWhere('c.isalive = true')
                ->setParameter('farmId', $id)
                ->getQuery()
                ->getSingleScalarResult() ?? 0.0;

            $totalFeed = $cowRepository->createQueryBuilder('c')
                ->select('SUM(c.foodperweek)')
                ->where('c.farm = :farmId')
                ->andWhere('c.isalive = true')
                ->setParameter('farmId', $id)
                ->getQuery()
                ->getSingleScalarResult() ?? 0.0;

            $youngHighFeedCount = $cowRepository->createQueryBuilder('c')
                ->select('COUNT(c.id)')
                ->where('c.farm = :farmId')
                ->andWhere('c.isalive = true')
                ->andWhere('c.birthdate >= :dateLimit1Year')
                ->andWhere('c.foodperweek > :feedLimit')
                ->setParameter('farmId', $id)
                ->setParameter('dateLimit1Year', new \DateTime('-1 year'))
                ->setParameter('feedLimit', 500.0)
                ->getQuery()
                ->getSingleScalarResult();

            return $this->render('farm/reports.html.twig', [
                'slaughteredCows' => $slaughteredCows,
                'totalMilk' => $totalMilk,
                'totalFeed' => $totalFeed,
                'youngHighFeedCount' => $youngHighFeedCount,
            ]);
        }
    }