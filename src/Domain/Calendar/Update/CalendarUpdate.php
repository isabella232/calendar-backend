<?php declare(strict_types=1);

/**
 * @license  http://opensource.org/licenses/mit-license.php MIT
 * @link     https://github.com/nicoSWD
 * @author   Nicolas Oelgart <nico@oelgart.com>
 */
namespace App\Domain\Calendar\Update;

use App\Domain\Calendar\Update\Exception\CalendarProviderException;
use App\Domain\Calendar\Update\Exception\CalendarStorageException;

final readonly class CalendarUpdate
{
    public function __construct(
        private CalendarProvider $calendarProvider,
        private CalendarCompatibility $calendarCompatibility,
        private CalendarStorage $storage,
    ) {
    }

    public function update(): CalendarUpdateResult
    {
        try {
            $calendar = $this->calendarProvider->fetchLatestRelease();
        } catch (CalendarProviderException) {
            return new CalendarUpdateResult(
                status: CalendarUpdateStatus::DOWNLOAD_FAILED,
                errorMessage: 'Unable to download new calendar',
            );
        }

        $compatibilityResult = $this->calendarCompatibility->isCompatible($calendar);

        if (!$compatibilityResult->isCompatible) {
            return new CalendarUpdateResult(
                status: CalendarUpdateStatus::UPDATE_INCOMPATIBLE,
                errorMessage: $compatibilityResult->errorMsg,
            );
        }

        try {
            $this->storage->persist($calendar);
        } catch (CalendarStorageException) {
            return new CalendarUpdateResult(
                status: CalendarUpdateStatus::UPDATE_FAILURE,
                errorMessage: 'Unable to save new calendar',
            );
        }

        return new CalendarUpdateResult(
            status: CalendarUpdateStatus::UPDATE_SUCCESS,
        );
    }
}
