# Data Tools
Data Tools is a simple library for basic operations on data. This library simplifies simple operations that sometimes might be required during development. It also adds a few extra capabilities by using objects.

## Installation
Installing Data Tools can be done through composer. This is done with the following command:
```
composer require jyxon/data-tools
```

## Usage
The package is separated into 2 namespace (currently).

### Native
Within the Native namespace we have defined packages that are simple manipulators of native data types and object of PHP.

#### ArrayTool
The `ArrayTool` is a tool that enables paths within arrays. It also allows flattening of an array in this manner and expanding a flattened array. For the `ArrayTool` a `path` or `flat path` is a "/" (slash) separated path (foo/bar). A `array path` is basically the result of an exploded path (the result of `explode('/', $path)`).

The `ArrayTool` can be initialised as follows:
```php
use Jyxon\DataTools\Native\ArrayTool;

$associative_array = [
    'foo' => [
        'bar' => 'baz'
    ]
];

$array = new ArrayTool($associative_array);
```

The following functions are available for the `ArrayTool`:

```php
/**
 * The array is expanded and multidimensional.
 */
ArrayTool::STATUS_EXPANDED;

/**
 * The array is flat and one dimensional.
 */
ArrayTool::STATUS_FLAT;

/**
 * Constructor
 *
 * @param array $array The array that needs to be loaded into the tool.
 * @param int $type The type of the array. Can either be ArrayTool::STATUS_EXPANDED or ArrayTool::STATUS_FLAT
 */
public function __construct(array $array, int $type = self::STATUS_EXPANDED);

/**
 * Returns the array in it's current state.
 *
 * @return array
 */
public function getArray(): array;

/**
 * Loads a new array into the tool.
 *
 * @return ArrayTool
 */
public function setArray(array $array, int $type = self::STATUS_EXPANDED): ArrayTool;

/**
 * Expands the array from the flattened form.
 *
 * @return ArrayTool
 */
public function expandArray(): ArrayTool;

/**
 * Flattens the array.
 *
 * @return ArrayTool
 */
public function flattenArray(): ArrayTool;

/**
 * Fetches a value in an array through a "/" separated path.
 *
 * @param string $path
 *
 * @return mixed
 */
public function getByPath(string $path);

/**
 * Sets an entry by a path.
 *
 * @param string $path
 * @param mixed $value
 *
 * @return ArrayTool
 */
public function setByPath(string $path, $value): ArrayTool;

/**
 * Strips all empty strings from an array (array_filter, but explicitly with strings).
 *
 * @return ArrayTool
 */
public function stripEmptyString(): ArrayTool;

/**
 * Fetches the value within an array by the given array path.
 *
 * @return mixed
 */
public function getArrayValueByArrayPath(array $path);

/**
 * Sets a value in the array by an array path.
 *
 * @param array $path
 * @param mixed $value
 *
 * @return ArrayTool
 */
public function setArrayValueByArrayPath(array $path, $value): ArrayTool;

/**
 * Creates a subarray wether the mixed variable is an array or not.
 * If the mixed variable is an array it creates an entry.
 * If it is not an array it will create an array with the key set as a variable.
 *
 * @param mixed $mixed
 * @param string $key
 *
 * @return array
 */
public function forceSubArrayKey($mixed, string $key): array;
```

#### DataObject
The `DataObject` is not so much a tool, but more a basis for 3 standard functions for an object. You could use it as a basis, but also as an Object on it's own as a data container. You can either choose to let an existing class extend the `DataObject` to "add" the functionality to the class. Or you can choose to initialise it on it's own:
```php
use Jyxon\DataTools\Native\DataObjet;

$container = new DataObject();
```

The functions that this object adds are:
```php
/**
 * Sets data on the object.
 *
 * @param string $key
 * @param mixed $value
 */
public function setData(string $key, $value);

/**
 * Retrieves the value of the object.
 *
 * @param string $key
 *
 * @return mixed
 */
public function getData(string $key = '');

/**
 * Check wether the value is set or not.
 *
 * @param string $key
 *
 * @return boolean
 */
public function hasData(string $key): bool;
```

#### ReflectorTool
The `ReflectorTool` adds a few extra functions to the initial `ReflectionClass` native to PHP. It can be initialised as follows:
```php
use Jyxon\DataTools\Native\ReflectorTool;

$reflection = new ReflectorTool('\\Foo\\Bar\\Baz');
```

The `ReflectorTool` adds the following functions:
```php
/**
 * Returns the type of the object as defined in the constants.
 *
 * @return int This can be ReflectorTool::TYPE_UNKNOWN, ReflectorTool::TYPE_INTERFACE, ReflectorTool::TYPE_CLASS or ReflectorTool::TYPE_TRAIT.
 */
public function getType(): int;

/**
 * Fetches the constructor arguments.
 *
 * @return bool|ReflectionParameter[]
 */
public function getConstructorArgs();
```

### Operation
Within the Operation namespace we have defined packages that are not native to PHP, but add functionality or simplify standard operations.

#### CalculationTool
The `CalculationTool` exposes some calculations that can be tedious to implement.

It can be initialised as follows:
```php
use Jyxon\DataTools\Operation\CalculationTool;

$calculator = new CalculationTool();
```

It exposes the following functions:
```php
/**
 * Gets the percentage of a factor based on a base number.
 *
 * @param float $baseNumber
 * @param float $factorNumber
 * @param float $basePercentage
 *
 * @return float
 */
public function getPercentageOfFactor(float $baseNumber, float $factorNumber, float $basePercentage = 1): float;

/**
 * Gets the factor of a percentage based on a base number.
 *
 * @param float $baseNumber
 * @param float $percentage
 * @param float $basePercentage
 *
 * @return float
 */
public function getFactorOfPercentage(float $baseNumber, float $percentage, float $basePercentage = 1): float;

/**
 * Adds a percentage to a base number.
 *
 * @param float $baseNumber
 * @param float $percentage
 * @param float $basePercentage
 *
 * @return float
 */
public function addPercentage(float $baseNumber, float $percentage, float $basePercentage = 1): float;

/**
 * Subtracts a percentage of a base number.
 *
 * @param float $baseNumber
 * @param float $percentage
 * @param float $basePercentage
 *
 * @return float
 */
public function subtractPercentage(float $baseNumber, float $percentage, float $basePercentage = 1): float;

/**
 * Returns the average of an array.
 *
 * @param array $numbers
 *
 * @return float
 */
public function average(array $numbers): float;

/**
 * Returns the median of an array of numbers.
 *
 * @param array $numbers
 *
 * @return float
 */
public function median(array $numbers): float;

/**
 * Returns horizon distance between 2 points on earth.
 * Using the Haversine formula.
 *
 * @param float $lat1
 * @param float $lon1
 * @param float $lat2
 * @param float $lon2
 *
 * @return float Distance in KM
 */
public function distance(float $lat1, float $lon1, float $lat2, float $lon2);

/**
 * Calculates the correlation between 2 sets of data.
 *
 * @param float[] $x
 * @param float[] $y
 *
 * @return float
 */
public function correlation(array $x, array $y): float;

/**
 * Calculates the offset between 2 arrays.
 *
 * @param float[] $x
 * @param float[] $y
 *
 * @return float
 */
public function offset(array $x, array $y): float;

/**
 * Checks wether the provided array are similar or not.
 *
 * @param float[] $x
 * @param float[] $y
 *
 * @return float
 */
public function similarity(array $x, array $y): float;

/**
 * Runs the similarity function with sorted arrays.
 *
 * @param float[] $x
 * @param float[] $y
 *
 * @return float
 */
public function sortedSimilarity(array $x, array $y): float;
```

#### PathTool
The `PathTool` is a parser for paths. It turns readable paths into useable paths for your application.
```php
use Jyxon\DataTools\Operation\PathTool;

$path = new PathTool('foo/bar/baz?foo=bar', true);
```

It exposes the following functions:
```php
/**
 * Constructor
 *
 * @param string $path
 * @param bool $parseQuery
 */
public function __construct(string $path, bool $parseQuery = false);

/**
 * Returns the path that is set.
 *
 * @return string
 */
public function getPath(): string;

/**
 * Sets the path that needs to be parsed.
 *
 * @param string $path
 *
 * @return void
 */
public function setPath(string $path);

/**
 * Returns the previous path.
 *
 * @return PathTool
 */
public function getPreviousPath(): PathTool;

/**
 * Reverts the current object to the last.
 *
 * @return void
 */
public function revertToPreviousPath();

/**
 * Returns the expanded path.
 *
 * @return array
 */
public function getExpanded(): array;

/**
 * Merges multiple paths into the current path.
 *
 * @param string ...$param
 *
 * @return void
 */
public function mergePaths();

/**
 * Returns an associative array of the parameters from the path.
 *
 * @return array
 */
public function getParameters(): array;
```

### VersionTool
A tool to analyse versions for your application.

It can be initialised as follows:
```php
use Jyxon\DataTools\Operation\VersionTool;

$path = new VersionTool('1.2.3');
```

It exposes the following functions:
```php
/**
 * Constructor
 *
 * @param string $version
 */
public function __construct(string $version);

/**
 * Compares one version to another.
 *
 * @param VersionTool $version
 *
 * @return bool
 */
public function versionCompare(VersionTool $version, string $operator): bool;

/**
 * Resets the object with a new version.
 *
 * @param string $version
 *
 * @return void
 */
public function setVersion(string $version);

/**
 * Returns the version that is set within the VersionTool.
 *
 * @return string
 */
public function getVersion(): string;

/**
 * __toString interpreter hook.
 *
 * @return string
 */
public function __toString(): string;

/**
 * Returns the version depth.
 *
 * @return int
 */
public function getVersionDepth(): int;

/**
 * Returns the expanded version.
 *
 * @return array
 */
public function getExpandedVersion(): array;

/**
 * Returns the version number at a certain depth.
 *
 * @param int $depth
 *
 * @return int
 */
public function getVersionAt(int $depth): int
```

## Feedback
We like to get some feedback on this package. You can do so by creating an issue on GitHub.
Also keep in mind that this package might receive quite some updates in the near future, due to our own development process requiring this package to change.
