<?php

namespace App\Controller;

use App\Repository\UserRepository;
use App\Repository\PaymentRepository;
use App\Repository\TripDayRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/statistics')]
class StatisticsController extends AbstractController
{
    #[Route('', name: 'app_statistics')]
    public function index(
        UserRepository $userRepo,
        PaymentRepository $paymentRepo,
        TripDayRepository $tripDayRepo,
        EntityManagerInterface $em
    ): Response {
        // number of clients
        $nbClients = (int) $userRepo->count([]);

        // number of "reservations" -- using payments as proxy
        $nbReservations = (int) $paymentRepo->count([]);

        // total revenue (sum of payment.amount), safely return 0 if null
        $qb = $paymentRepo->createQueryBuilder('p')
            ->select('COALESCE(SUM(p.amount), 0) AS total');
        $totalResult = $qb->getQuery()->getSingleScalarResult();
        $totalRevenue = (float) $totalResult;

        // Top services (TripDay) by number of payments
        // select TripDay entity + count
       // Top services (TripDay) by number of payments
$qb2 = $paymentRepo->createQueryBuilder('p')
    ->select('IDENTITY(p.tripDay) AS tripDayId, COUNT(p.id) AS cnt')
    ->leftJoin('p.tripDay', 'td')
    ->groupBy('p.tripDay')
    ->orderBy('cnt', 'DESC')
    ->setMaxResults(5);

$topRows = $qb2->getQuery()->getResult();

$topServices = [];
foreach ($topRows as $row) {
    $tripDayId = $row['tripDayId'];
    $cnt = (int) $row['cnt'];

    if ($tripDayId !== null) {
        $td = $tripDayRepo->find($tripDayId);

        if ($td) {
            if (method_exists($td, 'getName')) $name = $td->getName();
            elseif (method_exists($td, 'getTitle')) $name = $td->getTitle();
            else $name = 'TripDay #' . $td->getId();
        } else {
            $name = "Unknown";
        }
    } else {
        $name = "Unknown";
    }

    $topServices[] = [
        'name' => $name,
        'count' => $cnt,
    ];
}


        $topServices = [];
        foreach ($topRows as $row) {
            // Doctrine returns array keys with numeric + alias; normalize
            if (is_array($row)) {
                $td = $row['tripDay'] ?? ($row[0] ?? null);
                $cnt = isset($row['cnt']) ? (int)$row['cnt'] : (isset($row[1]) ? (int)$row[1] : 0);
            } else {
                // fallback
                $td = $row;
                $cnt = 0;
            }

            if ($td) {
                // try to get a friendly name from TripDay entity
                if (method_exists($td, 'getName')) {
                    $label = $td->getName();
                } elseif (method_exists($td, 'getTitle')) {
                    $label = $td->getTitle();
                } elseif (method_exists($td, 'getLabel')) {
                    $label = $td->getLabel();
                } else {
                    $label = 'TripDay #' . $td->getId();
                }
                $topServices[] = ['name' => $label, 'count' => $cnt];
            } else {
                // payments without a tripDay
                $topServices[] = ['name' => 'Unknown', 'count' => $cnt];
            }
        }

        // Monthly revenue chart (last 6 months) using raw SQL for simplicity
        $conn = $em->getConnection();
        $sql = <<<SQL
SELECT DATE_FORMAT(create_at, '%Y-%m') AS month, COALESCE(SUM(amount),0) AS total
FROM payment
WHERE create_at IS NOT NULL
GROUP BY month
ORDER BY month DESC
LIMIT 6
SQL;
        $stmt = $conn->prepare($sql);
        $result = $stmt->executeQuery()->fetchAllAssociative();

        // We want chronological order (old -> new)
        $result = array_reverse($result);

        $chartLabels = [];
        $chartData = [];
        foreach ($result as $r) {
            $chartLabels[] = $r['month'];
            $chartData[] = (float)$r['total'];
        }

        // If no rows (empty), provide placeholders
        if (empty($chartLabels)) {
            $chartLabels = [];
            $chartData = [];
        }

        return $this->render('statistics/statistics.html.twig', [
            'nbClients' => $nbClients,
            'nbReservations' => $nbReservations,
            'totalRevenue' => $totalRevenue,
            'topServices' => $topServices,
            'chartLabels' => $chartLabels,
            'chartData' => $chartData,
        ]);
    }
}
