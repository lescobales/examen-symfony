<?php

namespace App\Twig\Extension;

use App\Twig\Runtime\PriceExtensionRuntime;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class PriceExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            // If your filter generates SAFE HTML, you should add a third
            // parameter: ['is_safe' => ['html']]
            // Reference: https://twig.symfony.com/doc/3.x/advanced.html#automatic-escaping
            new TwigFilter('price', [PriceExtensionRuntime::class, 'price']),
        ];
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('function_name', [PriceExtensionRuntime::class, 'doSomething']),
        ];
    }
}
