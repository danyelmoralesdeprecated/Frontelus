<?php
namespace Frontelus\Library\Benchmark;
abstract class BenchmarkPerformance
{    
    public function __construct(){}
 
    public function check($loops = '')
    {
        ob_start();
        $loops = ($loops === '')?10000:$loops;
        $start = microtime(true);
        
        for ($i = 0; $i < $loops; $i++)
        {
            $this->method();
        }
        
        $total = microtime(true) - $start;
        $avg = $total / $loops;
        ob_end_clean();
        echo "Loops: $loops \n";
        echo "Average: [" . $avg . "  ms/loops]\n [$total ms]\n";
        return $total;
    }
    
    public function check_mem($loops = '')
    {
        $mem = memory_get_usage();
        $this->check($loops);
        $realMem = (memory_get_usage() - $mem) / (1024 * 1024);
        echo "Memory Usage: " . $realMem . "KB";
    }
    
    public function Ttest($loops = '', $loops2  = '')
    {
        $total = 0;
        for ($i = 0; $i < $loops; $i++)
        {
            $total += $this->check($loops2);
        }
        
        $finally = $total / $loops;
        echo "t = $finally \n";
    }
    
    abstract public function method();
}
