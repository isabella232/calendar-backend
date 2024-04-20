<?php

/**
 * @license  http://opensource.org/licenses/mit-license.php MIT
 * @link     https://github.com/nicoSWD
 * @author   Nicolas Oelgart <nico@oelgart.com>
 */
namespace App\Domain\Event;

interface EventProviderInterface
{
    /** @return Event[] */
    public function fetchEvents(): array;
}
