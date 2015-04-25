<?php namespace Origami\Support\Entities;

trait ReferenceTrait {

    public function findByRef($reference, $column = 'ref')
    {
        return $this->newQuery()->where($column,'=',$reference)->first();
    }

    public function existsByRef($reference, $column = 'ref')
    {
        return ( $this->newQuery()->where($column,'=',$reference)->count() > 0 );
    }

    public function newUniqueRef($query, $length = 5, $column = 'ref', $type = 'numeric')
    {
        $ref = $this->newRandomRef($length, $type);

        $tries = 1;

        while ( $query->where($column,'=',$ref)->count() > 0 ) {
            $tries++;
            $ref = $this->newRandomRef($length, $type);
            if ( $tries%5 ) {
                $length++;
            }
        }

        return $ref;
    }

    protected function newRandomRef($length, $type = 'numeric')
    {
        switch ( $type ) {
            case 'alphanumeric':
                $pool = '0123456789abcdefghijklmnopqrstuvwxyz';
                break;
            case 'numeric':
            default:
                $pool = '123456789';
        }

        return substr(str_shuffle(str_repeat($pool, $length)), 0, $length);
    }

    abstract protected function newQuery();

}