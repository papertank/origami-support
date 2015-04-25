<?php namespace Origami\Support\Entities;

trait PaginateTrait {

    protected $per_page = 20;

    protected function buildPaginatedResults($query, $columns = ['*'])
    {
        return $query->paginate($this->per_page, $columns);
    }

}