<?php
declare(strict_types=1);

namespace Flynn314\DataField;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

/**
 * @property array data
 * @mixin Model
 */
trait DataFieldSetGet
{
    public const string DATA = 'data';

    public function getAttribute($key)
    {
        $reflection = new \ReflectionClass($this);
        $attributes = $reflection->getAttributes(DataFields::class);

        if ($attributes !== []) {
            /** @var DataFields $publicData */
            $publicData = $attributes[0]->newInstance();

            if (in_array($key, $publicData->columns)) {
                return $this->getData($key);
            }
        }

        return parent::getAttribute($key);
    }

    public function setAttribute($key, $value): mixed
    {
        $reflection = new \ReflectionClass($this);
        $attributes = $reflection->getAttributes(DataFields::class);

        if ($attributes !== []) {
            /** @var DataFields $publicData */
            $publicData = $attributes[0]->newInstance();

            if (in_array($key, $publicData->columns)) {
                $this->setData($key, $value);

                return $this;
            }
        }

        return parent::setAttribute($key, $value);
    }

    public function getData(string $key, mixed $default = null): mixed
    {
        if (Str::contains($key, '.')) {
            [$one, $two] = explode('.', $key, 2);
            return $this->data[$one][$two] ?? $default;
        }

        $value = $this->data[$key] ?? $default;

        $casts = $this->casts();
        if (isset($casts[$key]) && 'boolean' === $casts[$key]) {
            return (bool) $value;
        } elseif (isset($casts[$key]) && 'datetime' === $casts[$key] && $value) {
            return \DateTime::createFromFormat('Y-m-d H:i:s', $value);
        } elseif (isset($casts[$key])) {
            if ('array' === $casts[$key] && is_array($value)) {
                // all good
            } else {
                Log::warning('Unhandled data field cast', [
                    'key' => $key,
                    'cast' => $casts[$key],
                    'is_array' => is_array($value),
                    'is_string' => is_string($value),
                    'is_numeric' => is_numeric($value),
                    'is_null' => null === $value,
                ]);
            }
        }

        return $value;
    }

    public function setData(string $key, mixed $value): void
    {
        if (null === $value) {
            $this->unsetData($key);

            return;
        }
        $casts = $this->casts();
        if (isset($casts[$key]) && 'boolean' === $casts[$key]) {
            $value = (bool) $value;
        } elseif (isset($casts[$key]) && 'datetime' === $casts[$key]) {
            if ($value instanceof \DateTimeInterface) {
                $value = $value->format('Y-m-d H:i:s');
            }
        } elseif (isset($casts[$key]) && 'hashed' === $casts[$key]) {
            $value = Hash::make($value);
        } elseif (isset($casts[$key])) {
            if ('array' === $casts[$key] && is_array($value)) {
                // all good
            } else {
                Log::warning('Unhandled data field cast 2', [
                    'cast' => $casts[$key],
                    'is_array' => is_array($value),
                    'is_string' => is_string($value),
                    'is_numeric' => is_numeric($value),
                    'is_null' => null === $value,
                ]);
            }
        }

        $data = $this->data;
        if (Str::endsWith($key, '.')) {
            $key = Str::rtrim($key, '.');
            $data[$key][] = $value;
        } elseif (Str::contains($key, '.')) {
            [$one, $two] = explode('.', $key, 2);
            $data[$one][$two] = $value;
        } else {
            $data[$key] = $value;
        }
        $this->data = $data;
    }

    public function unsetData(string $key): void
    {
        if (Str::contains($key, '.')) {
            [$one, $two] = explode('.', $key, 2);
            $data = $this->data;
            if (isset($data[$one][$two])) {
                unset($data[$one][$two]);
            }
            if (isset($data[$one]) && !$data[$one]) {
                unset($data[$one]);
            }
            $this->data = $data;
        } elseif (isset($this->data[$key])) {
            $data = $this->data;
            unset($data[$key]);
            $this->data = $data;
        }
    }

    public function save(array $options = []): bool
    {
        if (!$this->data) {
            $this->data = [];
        }

        return parent::save($options);
    }

    public function toArray(): array
    {
        $data = parent::toArray();

        $reflection = new \ReflectionClass($this);
        $attributes = $reflection->getAttributes(DataFields::class);

        if ($attributes !== []) {
            /** @var DataFields $publicData */
            $publicData = $attributes[0]->newInstance();

            foreach ($publicData->columns as $column) {
                $data[$column] = $this->getAttribute($column);
            }
        }

        return $data;
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function dataFieldCasts(): array
    {
        return [
            'data' => 'array',
        ];
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return array_merge($this->dataFieldCasts(), [
            // model casts goes here..
        ]);
    }
}
