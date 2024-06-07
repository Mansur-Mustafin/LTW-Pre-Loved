<?php

class Country
{
    public function __construct(
        public int $id,
        public string $name,
        public string $alpha2,
        public string $alpha3,
    ) {}

    public function ensureDefaultValues() {}
}
