<?php

namespace App\Filters;

use Illuminate\Http\Request;

abstract class Filters
{
	protected $request, $builder;

	protected $filters = [];

	/**
	 * Filters constructor.
	 *
	 * @param Request $request
	 */
	public function __construct(Request $request)
	{
		$this->request = $request;
	}

	public function apply($builder)
	{
		$this->builder = $builder;

		/**
		 * Otra opcion valida seria con un enfoque funcional.
		 * 
		collect($this->getFilters())
			->filter(function($value, $filter) {
				return method_exists($this, $filter);
			})
			->each(function($value, $filter) {
				$this->$filter($value);
			});
		*/
	
		foreach ($this->getFilters() as $filter => $value) {
			if (method_exists($this, $filter)) {
				$this->$filter($value);
			}
		}

        return $this->builder;
	}

	public function getFilters()
	{
		return $this->request->intersect($this->filters);

		// devuelve un array con los campos existente en filters y con
		// los valores de request (si dispone de los campos de filters).
		// Da error, si pasamos "/threads" intentaria procesar como si
		// fuese "/threads?by=null".
		// 
		// return $this->request->only($this->filters);
	}

	/*
	public function apply($builder)
	{
		$this->builder = $builder;

		foreach ($this->filters as $filter) {
			if (! $this->hasFilter($filter)) return;
			$this->$filter($this->request->$filter);
		}

        return $this->builder;
	}

	public function hasFilter($filter): bool
	{
		return method_exists($this, $filter) && $this->request->has($filter);
	}
	*/
}