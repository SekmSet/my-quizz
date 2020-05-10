<?php


namespace App\Services;


use App\Repository\CategorieRepository;

class CategorieServices
{
    /**
     * @var CategorieRepository
     */
    private $categorieRepository;

    /**
     * QuizzServices constructor.
     * @param CategorieRepository $categorieRepository
     */
    public function __construct(CategorieRepository $categorieRepository)
    {
        $this->categorieRepository = $categorieRepository;
    }

    public function get_categories(){
        return $this->categorieRepository->findAll();
    }
}