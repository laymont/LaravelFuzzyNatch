<?php

use Pest\Plugins\Actions\CoversNothing;
use Pest\Plugins\Init;

$init = new Init(__DIR__);
$init->infect(__DIR__);

\CoversNothing::disable();
