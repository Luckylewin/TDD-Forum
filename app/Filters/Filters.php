<?php
/**
 * Created by PhpStorm.
 * User: lychee
 * Date: 2018/12/1
 * Time: 15:08
 */

namespace App\Filters;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Filters
{
    /**
     * @var Request
     */
    protected $request;
    /**
     * @var Model
     */
    protected $builder;
    protected $filters = [];

    /**
     * Filters constructor.
     * @param $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function apply($builder)
    {
        $this->builder = $builder;

        foreach ($this->getFilters() as $filter => $value) {
           if (method_exists($this, $filter)) {
                $this->$filter($value);
           }

            $this->$filter($this->request->$filter);
        }

        return $this->builder;
    }

    public function getFilters()
    {
        return array_filter($this->request->only($this->filters));
    }
}