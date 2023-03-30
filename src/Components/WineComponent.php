<?php

namespace  App\Components;

use App\Entity\Wine;
use App\Repository\WineRepository;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;


#[AsTwigComponent('wine')]
class WineComponent
{
    public int $id;
    public function __construct(
        private WineRepository $wineRepository
    ) {

    }
    public function getWine (): Wine
    {
        return $this->wineRepository->find($this->id);
    }
}