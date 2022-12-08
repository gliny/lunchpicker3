<?php

namespace App\Controller;

use App\Repository\DayRepository;
use App\Service\LunchLoader;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LunchLoadController extends AbstractController
{
    /**
     * @Route("/load-lunches", name="app_lunch_load")
     */
    public function index(LunchLoader $lunchLoader, LoggerInterface $logger): Response
    {
        $savedLunchesCount = $lunchLoader->refreshLunches();
        if($savedLunchesCount > 0){
            $logger->log(LogLevel::INFO, '$savedLunchesCount: ' . $savedLunchesCount);
        }
        return $this->render('lunch_load/index.html.twig', [
            'savedLunchesCount' => $savedLunchesCount,
        ]);
    }

    /**
     * @Route("/list-lunches", name="app_lunch_list")
     */
    public function list(DayRepository $dayRepository): Response
    {

        return $this->render('lunch_load/list.html.twig', [
            'days' => $dayRepository->findNewerThanYesterday(),
        ]);
    }
}
