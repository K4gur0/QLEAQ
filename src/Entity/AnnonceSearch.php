<?php

namespace App\Entity;

class AnnonceSearch
{

    /**
     * @var string|null
     */
    private $datemin;

    /**
     * @var string|null
     */
    private $datemax;

    public function getDatemin()
    {
        return $this->datemin;
    }

    public function setDatemin($datemin): self
    {
        $this->datemin = $datemin;

        return $this;
    }

    public function getDatemax()
    {
        return $this->datemax;
    }

    public function setDatemax($datemax): self
    {
        $this->datemax = $datemax;

        return $this;
    }
}
