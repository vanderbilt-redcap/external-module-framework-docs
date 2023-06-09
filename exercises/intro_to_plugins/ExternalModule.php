<?php

namespace HelloPlugin\ExternalModule;

use ExternalModules\AbstractExternalModule;

class ExternalModule extends AbstractExternalModule {

    function sayHello() {
        print_r("Hello, world!");
    }

}
