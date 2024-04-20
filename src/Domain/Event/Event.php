<?php declare(strict_types=1);

/**
 * @license  http://opensource.org/licenses/mit-license.php MIT
 * @link     https://github.com/nicoSWD
 * @author   Nicolas Oelgart <nico@oelgart.com>
 */
namespace App\Domain\Event;

use App\Domain\Athlete\Athlete;
use App\Domain\Round\Discipline;
use App\Domain\Round\Round;
use DateTimeImmutable;
use DateTimeZone;

final class Event
{
    public int $id;
    public string $leagueName;
    public int $season;
    public string $name;
    public string $country;
    public string $location;
    public ?string $poster;
    public string $siteUrl;
    public string $eventUrl;
    /** @var Discipline[] */
    public array $disciplines;
    public DateTimeImmutable $startsAt;
    public DateTimeImmutable $endsAt;
    public DateTimeZone $timezone;
    /** @var Round[] */
    public array $rounds;
    /** @var Athlete[] */
    public array $startList;
    public bool $hasUnconfirmedRounds;
}
