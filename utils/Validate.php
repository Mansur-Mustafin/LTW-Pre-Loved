<?php

declare(strict_types=1);


class Validate
{
    private int $code;
    private bool $throwOnFirst = false;
    private array $errors = [];

    public function __construct(
        private readonly array | object $provider,
    ) {}

    public static function in(array|object $provider): static
    {
        return new static($provider);
    }

    private function getAttributeValue(string $attribute)
    {
        if (is_array($this->provider)) {
            return $this->provider[$attribute];
        } elseif (is_object($this->provider)) {
            return $this->provider->$attribute;
        }

        throw new Exception('Error while getting attribute from provider', 500);
    }

    public function setThrowOnFirst(int $code = 500): static
    {
        $this->throwOnFirst = true;
        $this->code = $code;

        return $this;
    }

    protected function addError(string $attribute, string $errorMessage)
    {
        if ($this->throwOnFirst) {
            if (is_object($this->provider)) {
                $context = get_class($this->provider);
                $errorMessage .= " in object: {$context}";
            }

            throw new Exception("Error: {$errorMessage}", $this->code);
        }

        $this->errors[$attribute][] = $errorMessage;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    public function required(array $attributes): static
    {
        foreach ($attributes as $attribute) {

            $isSet = false;

            if (is_array($this->provider)) {
                $isSet = isset($this->provider[$attribute]);
            } elseif (is_object($this->provider)) {
                $isSet = isset($this->provider->$attribute);
            }

            if (!$isSet || $this->getAttributeValue($attribute) == '') {
                $this->addError($attribute, "{$attribute} is required");
            }
        }

        return $this;
    }

    public function length(array $attributes, int $min, int $max): static
    {
        foreach ($attributes as $attribute) {
            $attributeValue = $this->getAttributeValue($attribute);
            $length = strlen($attributeValue);

            $errorMessagePart = $min === $max ? $min : "from {$min} to {$max}";
            $errorMessage = "Length of {$attribute} must be {$errorMessagePart} symbols";

            if ($length < $min || $length > $max) {
                $this->addError($attribute, $errorMessage);
            }
        }

        return $this;
    }

    public function match(string $attribute, string $pattern): static
    {
        $attributeValue = $this->getAttributeValue($attribute);
        if (!preg_match($pattern, $attributeValue)) {
            $this->addError($attribute, "{$attribute} does not match the required format");
        }

        return $this;
    }

    public function int(array $attributes): static
    {
        foreach ($attributes as $attribute) {
            if (!is_numeric($this->getAttributeValue($attribute))) {
                $this->addError($attribute, "{$attribute} must be integer");
            }
        }

        return $this;
    }

    public function inList(array $attributes, array $list): static
    {
        foreach ($attributes as $attribute) {
            if (!in_array($this->getAttributeValue($attribute), $list)) {
                $this->addError($attribute, "The value of {$attribute} must be one of the allowed values");
            }
        }

        return $this;
    }
}
