<?php 

namespace Origami\Support;

use Illuminate\Http\Request;
use Illuminate\Routing\UrlGenerator;
use Collective\Html\HtmlBuilder;

class Filter {

    /**
     * @var Request
     */
    private $request;
    /**
     * @var UrlGenerator
     */
    private $url;
    /**
     * @var HtmlBuilder
     */
    private $html;

    protected $default = [
        'direction' => 'desc'
    ];

    public function __construct(Request $request, UrlGenerator $url, HtmlBuilder $html)
    {
        $this->request = $request;
        $this->url = $url;
        $this->html = $html;
    }

    public function sortByLink($key, $label, $default = null)
    {
        $default = $this->getSortDefaults($default);

        $sort = $this->request->input('sort');

        $direction = $sort ?
                        $this->oppositeSortDirection($this->getSortDirection($sort)) :
                        $default['direction'];

        $uri = $this->request->path();

        $query = array_merge($this->request->except('page'),[
            'sort' => $key.'.'.$direction,
        ]);

        $class = $this->getSortClass($key, $default['key'], $direction);

        return $this->html->link(
                $this->url->to($uri).'?'.http_build_query($query),
                $label,
                ( $class ? ['class' => $class] : [] )
            );
    }

    public function oppositeSortDirection($direction)
    {
        return ( $direction == 'asc' ? 'desc' : 'asc' );
    }

    protected function getSortClass($key, $default, $direction)
    {
        $class = null;

        if ( $this->request->input('sort', $default) == $key ) {
            $class = 'active sort-'.$direction;
        }

        return $class;
    }

    protected function getSortDirection($sort)
    {
        list($key, $direction) = explode('.', trim($sort));

        return $direction ?: null;
    }

    protected function getSortDefaults($default)
    {
        list($key, $direction) = array_merge( explode('.', trim($default)), [ null ] );

        return [
            'key' => $key,
            'direction' => ( is_null($direction) ? $this->default['direction'] : $direction )
        ];
    }

}