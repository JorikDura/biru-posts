<?php

declare(strict_types=1);

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use ReflectionClass;
use ReflectionException;

final readonly class CounterRule implements ValidationRule
{
    private const string ERROR_MESSAGE = "There's no '%s' method in %s model";

    public function __construct(
        private mixed $model,
        private string $relation,
        private int $max = 8
    ) {
    }

    /**
     * @param  string  $attribute
     * @param  mixed  $value
     * @param  Closure  $fail
     * @return void
     * @throws ReflectionException
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $reflection = new ReflectionClass($this->model);

        if (!$reflection->hasMethod($method = $this->relation)) {
            throw new ReflectionException(
                message: sprintf(self::ERROR_MESSAGE, $method, $reflection->getShortName())
            );
        }

        unset($reflection);

        $inputCount = count($value);
        $dbCount = $this->model->$method->count();

        if ($this->max < ($inputCount + $dbCount)) {
            $fail('validation.custom.counter')->translate([
                'attribute' => $attribute,
                'max' => $this->max
            ]);
        }
    }
}
