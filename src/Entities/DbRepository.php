<?php namespace Origami\Support\Entities;

use Illuminate\Support\Str;

abstract class DbRepository {

    public function random($limit = null)
    {
        $query = $this->newQuery()->orderByRaw('RAND()');

        if ( is_null($limit) ) {
            return $query->first();
        }

        return $query->take($limit)->get();
    }

    public function all()
    {
        return $this->newQuery()->get();
    }

    public function getById($id)
    {
        return $this->newQuery()->find($id);
    }

    public function findWhere($column, $value, $comparison = '=')
    {
        return $this->newQuery()->where($column, $comparison, $value)->first();
    }

    public function existsWhere($column, $value, $comparison = '=')
    {
        return ( $this->newQuery()->where($column, $comparison, $value)->count() > 0 );
    }

    protected function parseSortOrder($sort, $separator = '.')
    {
        list($order, $direction) = Str::contains($sort, $separator) ? explode($separator, $sort) : [$sort, null];

        return [
            'field' => $order,
            'direction' => ( in_array($direction, ['asc', 'desc']) ? $direction : 'desc' )
        ];
    }

    abstract protected function newQuery();

}
