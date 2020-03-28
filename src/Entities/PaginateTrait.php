<?php

namespace Origami\Support\Entities;

trait PaginateTrait
{
    protected $perPage = 20;

    public function setPerPage($number)
    {
        $this->perPage = $number;

        return $this;
    }

    public function getPerPage()
    {
        return $this->perPage;
    }

    protected function buildPaginatedResults($query, $columns = ['*'])
    {
        return $query->paginate($this->perPage, $columns);
    }
}
