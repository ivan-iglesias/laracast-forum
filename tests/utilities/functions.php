<?php

/*
 He añadido en "autoload-dev":
 "files": ["tests/utilities/functions.php"]
 */

function create($class, $attributes = [])
{
	return factory($class)->create($attributes);
}

function make($class, $attributes = [])
{
	return factory($class)->make($attributes);
}