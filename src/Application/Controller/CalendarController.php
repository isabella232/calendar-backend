<?php declare(strict_types=1);

/**
 * @license  http://opensource.org/licenses/mit-license.php MIT
 * @link     https://github.com/nicoSWD
 * @author   Nicolas Oelgart <nico@oelgart.com>
 */
namespace App\Application\Controller;

use App\Application\Component\HttpFoundation\CalendarResponse;
use App\Application\UseCase\Calendar\Build\BuildCalendarRequest;
use App\Application\UseCase\Calendar\Build\BuildCalendarUseCase;
use App\Domain\Filter\Filter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

final readonly class CalendarController
{
    public function __construct(
        private BuildCalendarUseCase $buildCalendar,
        private SerializerInterface $serializer,
    ) {
    }

    #[Route('/')]
    public function calendar(Request $request): CalendarResponse
    {
        $buildCalendarRequest = new BuildCalendarRequest(
            filter: $this->getFilters($request),
        );

        return new CalendarResponse(
            $this->buildCalendar->execute($buildCalendarRequest)->calendar,
        );
    }

    private function getFilters(Request $request): Filter
    {
        return $this->serializer->denormalize($request->query, Filter::class);
    }
}
