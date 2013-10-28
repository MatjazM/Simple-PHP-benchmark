Simple PHP benchmark
======

Simple PHP benchmark is a PHP library for benchmarks.
It's intended for comparing two pieces of PHP code and their corresponding execution time

Usage
-----

```php
require_once 'src/Benchmark.class.php';

$benchmark = new Benchmark;

for ($j = 0; $j < 10; $j++) {
    $benchmark->startTime('benchmark1');

    // execute some repeating code

    $benchmark->endTime('benchmark1');
}

for ($j = 0; $j < 10; $j++) {
    $benchmark->startTime('benchmark2');

    // execute some repeating code

    $benchmark->endTime('benchmark2');
}

$benchmark1 = $benchmark->showAverage('benchmark1');
$benchmark2 = $benchmark->showAverage('benchmark2');

$combinedUncertainties = $benchmark->roundToSignificantDigit(($benchmark1[1] / $benchmark1[0]) + ($benchmark2[1] / $benchmark2[0]), 2);

echo 'Mean time of benchmark1: ' . $benchmark1[0] . ' s +/- ' . $benchmark1[1] . ' s' . PHP_EOL;
echo 'Mean time of benchmark2: ' . $benchmark2[0] . ' s +/- ' . $benchmark2[1] . ' s' . PHP_EOL;
echo 'Improvement: ' . number_format((100 - (($benchmark2[0] / $benchmark1[0]) * 100)), 1) . ' % +/- ' . number_format($combinedUncertainties * 100, 1) . ' %' . PHP_EOL;
```

License
-------

Simple PHP benchmark is licensed under the MIT License
