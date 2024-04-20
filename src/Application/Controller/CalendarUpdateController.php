<?php declare(strict_types=1);

/**
 * @license  http://opensource.org/licenses/mit-license.php MIT
 * @link     https://github.com/nicoSWD
 * @author   Nicolas Oelgart <nico@oelgart.com>
 */
namespace App\Application\Controller;

use App\Application\Component\HttpFoundation\CalendarUpdateResponse;
use App\Application\UseCase\Calendar\Update\UpdateCalendarUseCase;
use Symfony\Component\Routing\Attribute\Route;

final readonly class CalendarUpdateController
{
    public function __construct(
        private UpdateCalendarUseCase $updateCalendarUseCase,
    ) {
    }

    #[Route('/webhook/notify-update')]
    public function update(): CalendarUpdateResponse
    {
        $updateResult = $this->updateCalendarUseCase->execute();

        return new CalendarUpdateResponse(
            status: $updateResult->status,
            errorMessage: $updateResult->errorMessage,
        );
    }
}
