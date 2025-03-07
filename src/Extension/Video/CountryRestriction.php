<?php

namespace SamDark\Sitemap\Extension\Video;


abstract class CountryRestriction
{
    private $countries;

    public function __construct(array $countries)
    {
        foreach ($countries as $country) {
            $this->validateCountry($country);
        }

        $this->countries = $countries;
    }

    private function validateCountry(string $country)
    {
        // TODO: ISO 3166
        throw new \Exception("Not yet implemented");
    }

    abstract public function areAllowed(): bool;
}
