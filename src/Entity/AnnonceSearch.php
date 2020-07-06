<?php

namespace App\Entity;

class AnnonceSearch
{

    /**
     * @var int|null
     */
    private $loyerMin;

    /**
     * @var int|null
     */
    private $loyerMax;

    /**
     * @return int|null
     */
    public function getLoyerMin()
    {
        return $this->loyerMin;
    }

    /**
     * @param int|null $loyerMin
     * @return AnnonceSearch
     */
    public function setLoyerMin($loyerMin): AnnonceSearch
    {
        $this->loyerMin = $loyerMin;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getLoyerMax()
    {
        return $this->loyerMax;
    }

    /**
     * @param int|null $loyerMax
     * @return AnnonceSearch
     */
    public function setLoyerMax($loyerMax): AnnonceSearch
    {
        $this->loyerMax = $loyerMax;
        return $this;
    }

}
