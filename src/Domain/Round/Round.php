<?php declare(strict_types=1);

/**
 * @license  http://opensource.org/licenses/mit-license.php MIT
 * @link     https://github.com/nicoSWD
 * @author   Nicolas Oelgart <nico@oelgart.com>
 */
namespace App\Domain\Round;

use DateTimeImmutable;

final class Round
{
    public string $name;
    /** @var Category[] */
    public array $categories;
    /** @var Discipline[] */
    public array $disciplines;
    public RoundKind $kind;
    public DateTimeImmutable $startsAt;
    public DateTimeImmutable $endsAt;
    public RoundScheduleStatus $scheduleStatus;
    public ?string $streamUrl;
    public array $streamBlockedRegions;
}
