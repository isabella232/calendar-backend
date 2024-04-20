<?php declare(strict_types=1);

/**
 * @license  http://opensource.org/licenses/mit-license.php MIT
 * @link     https://github.com/nicoSWD
 * @author   Nicolas Oelgart <nico@oelgart.com>
 */
namespace App\Application\UseCase\Calendar\Build;

use App\Domain\Calendar\Build\CalendarBuilder;

final readonly class BuildCalendarUseCase
{
    public function __construct(
        private CalendarBuilder $calendarBuilder,
    ) {
    }

    public function execute(BuildCalendarRequest $request): BuildCalendarResponse
    {
        $calendar = $this->calendarBuilder->buildWithFilter(
            filter: $request->filter,
        );

        return new BuildCalendarResponse($calendar);
    }
}
