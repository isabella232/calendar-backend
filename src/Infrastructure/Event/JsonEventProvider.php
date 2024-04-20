<?php declare(strict_types=1);

/**
 * @license  http://opensource.org/licenses/mit-license.php MIT
 * @link     https://github.com/nicoSWD
 * @author   Nicolas Oelgart <nico@oelgart.com>
 */
namespace App\Infrastructure\Event;

use App\Domain\Calendar\Calendar;
use App\Domain\Event\EventProviderInterface;
use League\Flysystem\Filesystem;
use League\Flysystem\FilesystemException;
use RuntimeException;
use Symfony\Component\Serializer\SerializerInterface;

final readonly class JsonEventProvider implements EventProviderInterface
{
    public function __construct(
        private string $calendarFile,
        private SerializerInterface $serializer,
        private Filesystem $filesystem,
    ) {
    }

    /**
     * @inheritdoc
     * @throws RuntimeException
     */
    public function fetchEvents(): array
    {
        $calendar = $this->deserialize(
            $this->getJsonCalendarContents(),
        );

        return $calendar->events;
    }

    private function deserialize(string $data): Calendar
    {
        return $this->serializer->deserialize($data, type: Calendar::class, format: 'json');
    }

    /** @throws RuntimeException */
    public function getJsonCalendarContents(): string
    {
        try {
            return $this->filesystem->read($this->calendarFile);
        } catch (FilesystemException $e) {
            throw new RuntimeException($e->getMessage());
        }
    }
}
