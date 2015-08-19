<?php namespace Origami\Support\Entities;

trait PaginateTrait {

    protected $per_page = 20;

    public function setPerPage($number)
    {
    	$this->per_page = $number;

    	return $this;
    }

    public function getPerPage()
    {
    	return $this->per_page;
    }

    protected function buildPaginatedResults($query, $columns = ['*'])
    {
        return $query->paginate($this->per_page, $columns);
    }

}