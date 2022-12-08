<?php

namespace App\Service;

use App\Entity\Day;
use App\Entity\Lunch;
use App\Repository\DayRepository;
use App\Repository\LunchRepository;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Symfony\Component\DomCrawler\Crawler;

class LunchLoader
{

    private $lunchListUrl = 'http://www.unibest.cz/';

    private $lunchRepository;
    private $dayRepository;

    private $savedLunches = 0;

    public function __construct(DayRepository $dayRepository, LunchRepository $lunchRepository)
    {
        $this->dayRepository = $dayRepository;
        $this->lunchRepository = $lunchRepository;
    }

    public function refreshLunches(): int
    {

        $html = file_get_contents($this->lunchListUrl);
        $crawler = new Crawler($html);

        $crawler->filter('#menu .slideshow_1 div')->each(function (Crawler $node, $i) {
            $beforeElm = null;

            foreach ($node->children() as $domElement) {
                $actualElm = $domElement;

                if ($actualElm->nodeName === 'ul' && $beforeElm->nodeName === 'p') {
                    $dayDate = $beforeElm->textContent;
                    $res = preg_replace("/[^0-9.]/", "", $dayDate);
                    $datetime = new \DateTime($res);

                    $now = new \DateTime();
                    if($datetime < $now->modify("-1 day")){
                        continue;
                    }
                    if($datetime->format('N') > 5){
                        continue;
                    }

                    $dayEntity = new Day();
                    $dayEntity->setDate($datetime);

                    $saveDay = $this->dayRepository->findByDay($dayEntity);
                    if(!$saveDay){
                        $this->dayRepository->save($dayEntity, true);
                        $saveDay = $dayEntity;
                    }

                    $isFirst = true;
                    $counter = 1;
                    foreach ($actualElm->childNodes as $childNode) {
                        $lunchText = $childNode->textContent;

                        $lunch = new Lunch();
                        $lunch->setDay($saveDay);

                        if(is_numeric(substr($lunchText, 0, 1))){
                            $lunch->setNumber($counter);
                            $lunch->setSelectable(true);
                            $counter++;
                        }else{
                            $lunch->setSelectable(false);
                        }

                        $lunchText = ltrim($lunchText, "1234. ");
                        $lunch->setText($lunchText);

                        $savedLunch = $this->lunchRepository->findOneByLunch($lunch);
                        if(!$savedLunch){
                            $this->lunchRepository->save($lunch, true);
                            $this->savedLunches++;
                        }

                    }
                }

                $beforeElm = $actualElm;
            }


        });

        return $this->savedLunches;
    }

}