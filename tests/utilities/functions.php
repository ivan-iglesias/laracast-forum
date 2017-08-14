<?php

/*
 He añadido en "autoload-dev":
 "files": ["tests/utilities/functions.php"]
 */

function create($class, $attributes = [], $times = null)
{
	return factory($class, $times)->create($attributes);
}

function make($class, $attributes = [], $times = null)
{
	return factory($class, $times)->make($attributes);
}