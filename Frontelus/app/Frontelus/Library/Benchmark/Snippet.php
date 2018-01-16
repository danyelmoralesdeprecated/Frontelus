<?php

include "BenchmarkPerformance.clase.php";
use Frontelus\Library\Benchmark\BenchmarkPerformance;
class Foo extends BenchmarkPerformance
{
    
    public function __construct()
    {
        $this->Ttest(10);
    }

    public function method()
    {
        58+5;
    }

}

new Foo();