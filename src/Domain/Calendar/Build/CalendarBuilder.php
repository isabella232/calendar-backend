<?php declare(strict_types=1);

/**
 * @license  http://opensource.org/licenses/mit-license.php MIT
 * @link     https://github.com/nicoSWD
 * @author   Nicolas Oelgart <nico@oelgart.com>
 */
namespace App\Domain\Calendar\Build;

use App\Domain\Event\EventProviderInterface;
use App\Domain\Filter\Filter;

final readonly class CalendarBuilder
{
    public function __construct(
        private EventProviderInterface $eventProvider,
        private CalendarBuilderInterface $calendarBuilder,
    ) {
    }

    public function buildWithFilter(Filter $filter): string
    {
        return $this->calendarBuilder->generateForEvents(
            events: $this->eventProvider->fetchEvents(),
            filter: $filter,
        );
    }
}
