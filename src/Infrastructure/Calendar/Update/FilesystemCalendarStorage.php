<?php declare(strict_types=1);

/**
 * @license  http://opensource.org/licenses/mit-license.php MIT
 * @link     https://github.com/nicoSWD
 * @author   Nicolas Oelgart <nico@oelgart.com>
 */
namespace App\Infrastructure\Calendar\Update;

use App\Domain\Calendar\Update\CalendarStorage;
use App\Domain\Calendar\Update\Exception\CalendarStorageException;
use Exception;
use Override;
use Symfony\Component\Filesystem\Filesystem;

final readonly class FilesystemCalendarStorage implements CalendarStorage
{
    public function __construct(
        private Filesystem $filesystem,
        private string $calendarFile,
    ) {
    }

    /** @inheritdoc */
    #[Override] public function persist(string $calendar): void
    {
        try {
            $this->filesystem->dumpFile($this->calendarFile, $calendar);
        } catch (Exception $e) {
            throw new CalendarStorageException(
                $e->getMessage(),
            );
        }
    }
}
