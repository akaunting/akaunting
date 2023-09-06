<?php

namespace Plank\Mediable\UrlGenerators;

interface TemporaryUrlGeneratorInterface extends UrlGeneratorInterface
{
    public function getTemporaryUrl(\DateTimeInterface $expiry): string;
}
