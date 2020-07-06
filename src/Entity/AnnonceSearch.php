<?php

namespace App\Entity;

class AnnonceSearch
{

    /**
     * @var int|null
     */
    private $TarifMin;

    /**
     * @var int|null
     */
    private $TarifMax;

    /**
     * @return int|null
     */
    public function getTarifMin()
    {
        return $this->TarifMin;
    }

    /**
     * @param int|null $TarifMin
     * @return AnnonceSearch
     */
    public function setTarifMin($TarifMin): AnnonceSearch
    {
        $this->TarifMin = $TarifMin;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getTarifMax()
    {
        return $this->TarifMax;
    }

    /**
     * @param int|null $TarifMax
     * @return AnnonceSearch
     */
    public function setTarifMax($TarifMax): AnnonceSearch
    {
        $this->TarifMax = $TarifMax;
        return $this;
    }

}
