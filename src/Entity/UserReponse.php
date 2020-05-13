<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserReponseRepository")
 */
class UserReponse
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;



    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Reponse")
     * @ORM\JoinColumn(nullable=false)
     */
    private $reponse;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Historique", inversedBy="reponses")
     * @ORM\JoinColumn(nullable=false)
     */
    private $historique;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getReponse(): ?Reponse
    {
        return $this->reponse;
    }

    public function setReponse(?Reponse $reponse): self
    {
        $this->reponse = $reponse;

        return $this;
    }

    public function getHistorique(): ?Historique
    {
        return $this->historique;
    }

    public function setHistorique(?Historique $historique): self
    {
        $this->historique = $historique;

        return $this;
    }

    public function __toString()
    {
        return (string) $this->getReponse()->getReponse();
    }


}
