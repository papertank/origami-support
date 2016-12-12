<?php 

namespace Origami\Support\Entities;

use Exception;
use Illuminate\Database\Eloquent\Builder as Eloquent;
use Illuminate\Support\Facades\DB;

trait ReferenceTrait {

    public function findByRef($reference, $column = 'ref')
    {
        return $this->newQuery()->where($column,'=',$reference)->first();
    }

    public function existsByRef($reference, $column = 'ref')
    {
        return ( $this->newQuery()->where($column,'=',$reference)->count() > 0 );
    }

    public function newSequentialRef($query, $prefix = null, $length = 3, $column = 'ref')
    {
        if ( $query instanceof Eloquent ) {
            $query = $query->getModel()->newQueryWithoutScopes();
        }

        $last = $query->orderBy('created_at', 'desc')
                      ->orderBy($column, 'desc')
                      ->value($column);

        if ( ! $last ) {
            return $prefix . str_pad(1, $length, '0', STR_PAD_LEFT);
        }

        if ( strpos($last, $prefix) === 0 ) {
            $last = substr($last, strlen($prefix));
        }

        $next = (int) $last;

        do {

            $next++;
            $ref = $prefix . str_pad($next, $length, '0', STR_PAD_LEFT);
            $check = clone $query;

        } while ( $check->where($column,'=',$ref)->exists() );

        return $ref;
    }

    public function newUniqueRef($query, $length = 5, $column = 'ref', $type = 'numeric')
    {
        if ( $query instanceof Eloquent ) {
            $query = $query->getModel()->newQueryWithoutScopes();
        }

        $tries = 0;

        do {

            $tries++;
            if ( $tries%5 == 0 ) {
                $length++;
            }

            $ref = $this->newRandomRef($length, $type);
            $check = clone $query;

        } while ( $check->where($column,'=',$ref)->exists() );

        return $ref;
    }

    protected function newRandomRef($length, $type = 'numeric')
    {
        if ( $length < 3 ) {
            throw new Exception('Random ref cannot be less than 3');
        }

        switch ( $type ) {
            case 'alphanumeric':
                $pool = '0123456789abcdefghijklmnopqrstuvwxyz';
                return substr(str_shuffle(str_repeat($pool, $length)), 0, $length);
                break;
            case 'numeric':
                $ref = mt_rand(1,9);
                foreach ( range(2, $length) as $index ) {
                    $ref .= mt_rand(0,9);
                }
                return $ref;
            default:
                throw new Exception('Random type '.$type.' not supported');
        }
    }

    abstract protected function newQuery();

}