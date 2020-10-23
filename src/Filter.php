<?php

namespace Origami\Support;

use Illuminate\Http\Request;
use Illuminate\Routing\UrlGenerator;

class Filter
{

    /**
     * @var Request
     */
    private $request;
    /**
     * @var UrlGenerator
     */
    private $url;

    protected $default = [
        'direction' => 'desc'
    ];

    public function __construct(Request $request, UrlGenerator $url)
    {
        $this->request = $request;
        $this->url = $url;
    }

    public function sortByLink($key, $label, $default = null)
    {
        $default = $this->getSort($default);
        $sort = $this->getSort();

        $direction = $sort['direction'] ?
                        $this->oppositeSortDirection($sort['direction']) :
                        $default['direction'];

        $uri = $this->request->path();

        $query = array_merge($this->request->except('page'), [
            'sort' => $key.'.'.$direction,
        ]);

        $class = $this->getSortClass($key, $this->request->input('sort') ? $sort : $default);

        return $this->linkHtml(
            $this->url->to($uri).'?'.http_build_query($query),
            $label,
            $class,
        );
    }

    protected function linkHtml($url, $label, $class)
    {
        return '<a href="' . htmlentities($url, ENT_QUOTES, 'UTF-8', false).'"' . ($class ? ' class="'.$class.'"' : '').'>' . $label . '</a>';
    }

    public function oppositeSortDirection($direction)
    {
        return ($direction == 'asc' ? 'desc' : 'asc');
    }

    protected function getSortClass($key, $sort)
    {
        $class = 'sort';

        if ($sort['key'] == $key) {
            $class .= ' sort-active sort-'.$sort['direction'];
        }

        return $class;
    }

    protected function getSort($sort = null)
    {
        $sort = $sort ?: $this->request->input('sort');

        list($key, $direction) = array_merge(explode('.', trim($sort)), [ null ]);

        return [
            'key' => $key,
            'direction' => (is_null($direction) ? $this->default['direction'] : $direction)
        ];
    }
}
