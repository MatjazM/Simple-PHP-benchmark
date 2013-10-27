Simple PHP benchmark
======

Simple PHP benchmark is a PHP library for benchmarks
It's intended for comparing two pieces of PHP code and their corresponding execution time

Usage
-----

```php
require_once 'src/Benchmark.class.php';

$benchmark = new Benchmark;

for ($j = 0; $j < 10; $j++) {
    $benchmark->startTime('withIndex');

    // execute some repeating code

    $benchmark->endTime('withIndex');
}

for ($j = 0; $j < 10; $j++) {
    $benchmark->startTime('withoutIndex');

    // execute some repeating code

    $benchmark->endTime('withoutIndex');
}

$withoutIndex = $benchmark->showAverage('withoutIndex');
$withIndex = $benchmark->showAverage('withIndex');

$combinedUncertainties = $benchmark->roundToSignificantDigit(($withoutIndex[1] / $withoutIndex[0]) + ($withIndex[1] / $withIndex[0]), 2);

echo 'Mean time without index: ' . $withoutIndex[0] . ' s +/- ' . $withoutIndex[1] . ' s' . PHP_EOL;
echo 'Mean time with index: ' . $withIndex[0] . ' s +/- ' . $withIndex[1] . ' s' . PHP_EOL;
echo 'Improvement: ' . number_format((100 - (($withIndex[0] / $withoutIndex[0]) * 100)), 1) . ' % +/- ' . number_format($combinedUncertainties * 100, 1) . ' %' . PHP_EOL;
```

License
-------

Simple PHP benchmark is licensed under the MIT License