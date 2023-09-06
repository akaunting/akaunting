<?php

namespace Spatie\FlareClient\Context;

interface ContextProviderDetector
{
    public function detectCurrentContext(): ContextProvider;
}
