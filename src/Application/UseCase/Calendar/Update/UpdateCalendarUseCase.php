<?php declare(strict_types=1);

/**
 * @license  http://opensource.org/licenses/mit-license.php MIT
 * @link     https://github.com/nicoSWD
 * @author   Nicolas Oelgart <nico@oelgart.com>
 */
namespace App\Application\UseCase\Calendar\Update;

use App\Domain\Calendar\Update\CalendarUpdate;

final readonly class UpdateCalendarUseCase
{
    public function __construct(
        private CalendarUpdate $calendarUpdate,
    ) {
    }

    public function execute(): UpdateCalendarResponse
    {
        $updateResult = $this->calendarUpdate->update();

        return new UpdateCalendarResponse(
            status: $updateResult->status->value,
            errorMessage: $updateResult->errorMessage,
        );
    }
}
