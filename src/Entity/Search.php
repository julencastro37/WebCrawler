<?php

namespace App\Entity;
use Symfony\Component\Validator\Constraints as Assert;
class Search
{
    
    /**
     * @Assert\NotBlank()
     */
    public $search;

    public function getSearch()
    {
        return $this->search;
    }

    public function setSearch($search)
    {
        $this->search = $search;
    }
}
