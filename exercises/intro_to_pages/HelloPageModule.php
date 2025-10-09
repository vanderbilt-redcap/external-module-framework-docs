<?php

namespace ExternalModuleExercises\HelloPageModule;

use ExternalModules\AbstractExternalModule;

class HelloPageModule extends AbstractExternalModule
{
	public function sayHello() {
		print_r("Hello, world!");
	}

}
