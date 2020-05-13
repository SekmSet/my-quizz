<?php

namespace App\Services;

use App\Repository\HistoriqueRepository;

class HistoriqueServices
{
    /**
     * @var HistoriqueRepository
     */
    private $historiqueRepository;

    /**
     * QuizzServices constructor.
     * @param HistoriqueRepository $historiqueRepository
     */
    public function __construct(HistoriqueRepository $historiqueRepository)
    {
        $this->historiqueRepository = $historiqueRepository;
    }

    public function get_my_history($user){
        return $this->historiqueRepository->findBy(['user'=>$user]);
    }
}