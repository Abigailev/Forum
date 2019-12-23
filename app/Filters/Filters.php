<?php


namespace App\Filters;

//we dont intend u 4 instantiating this class directly, you'll always instanciate a sub class
use Illuminate\Http\Request;

abstract class Filters
{

    protected $request, $builder;

    /**
     * ThreadFilters constructor.
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function apply($builder)
    {
        $this->builder = $builder;

        foreach ($this->getFilters() as $filter => $value) {
            if (method_exists($this, $filter)) return;
                $this->$filter($value);
        }

        return $this->builder;

    }

    public function getFilters()
    {
        //ESTA VAINA NO SIRVE//return $this->request->intersect($this->filters);
        $filters = array_intersect(array_keys($this->request->all()), $this->filters);
        return $this->request->only($filters);
    }
}
