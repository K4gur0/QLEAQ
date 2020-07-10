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
     * @var int|null
     */
    private $SuperficieMin;

    /**
     * @var int|null
     */
    private $SuperficieMax;

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

    /**
     * @return int|null
     */
    public function getSuperficieMin()
    {
        return $this->SuperficieMin;
    }

    /**
     * @param int|null $SuperficieMin
     * @return AnnonceSearch
     */
    public function setSuperficieMin($SuperficieMin): AnnonceSearch
    {
        $this->SuperficieMin = $SuperficieMin;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getSuperficieMax()
    {
        return $this->SuperficieMax;
    }

    /**
     * @param int|null $SuperficieMax
     * @return AnnonceSearch
     */
    public function setSuperficieMax($SuperficieMax): AnnonceSearch
    {
        $this->SuperficieMax = $SuperficieMax;
        return $this;
    }

}
