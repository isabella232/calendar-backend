<?php declare(strict_types=1);

/**
 * @license  http://opensource.org/licenses/mit-license.php MIT
 * @link     https://github.com/nicoSWD
 * @author   Nicolas Oelgart <nico@oelgart.com>
 */
namespace App\Domain\Calendar\Build;

use App\Domain\Event\Event;
use App\Domain\Filter\Filter;

interface CalendarBuilderInterface
{
    /** @param Event[] $events */
    public function generateForEvents(array $events, Filter $filter): string;
}
