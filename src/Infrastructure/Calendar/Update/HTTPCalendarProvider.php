<?php declare(strict_types=1);

/**
 * @license  http://opensource.org/licenses/mit-license.php MIT
 * @link     https://github.com/nicoSWD
 * @author   Nicolas Oelgart <nico@oelgart.com>
 */
namespace App\Infrastructure\Calendar\Update;

use App\Domain\Calendar\Update\CalendarProvider;
use App\Domain\Calendar\Update\Exception\CalendarProviderException;
use Exception;
use Override;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;
use Throwable;

final readonly class HTTPCalendarProvider implements CalendarProvider
{
    private const int HTTP_OK = 200;

    public function __construct(
        private HttpClientInterface $client,
        private string $latestReleaseUrl,
    ) {
    }

    /** @inheritdoc */
    #[Override] public function fetchLatestRelease(): string
    {
        try {
            $response = $this->downloadCalendar();

            if ($response->getStatusCode() !== self::HTTP_OK) {
                throw new Exception();
            }

            $content = $response->getContent();

            if (empty($content)) {
                throw new Exception();
            }
        } catch (Throwable) {
            throw new CalendarProviderException();
        }

        return $content;
    }

    /** @throws TransportExceptionInterface */
    private function downloadCalendar(): ResponseInterface
    {
        return $this->client->request('GET', $this->latestReleaseUrl);
    }
}
