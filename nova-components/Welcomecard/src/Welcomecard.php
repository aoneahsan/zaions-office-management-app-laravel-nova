<?php

namespace Zaions\Welcomecard;

use Laravel\Nova\Card;

class Welcomecard extends Card
{
    /**
     * The width of the card (1/3, 1/2, or full).
     *
     * @var string
     */
    // public $width = '1/3';
    public $width = 'full';

    /**
     * Get the component name for the element.
     *
     * @return string
     */
    public function component()
    {
        return 'welcomecard';
    }
}
