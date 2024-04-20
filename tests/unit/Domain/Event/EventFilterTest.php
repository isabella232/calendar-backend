<?php declare(strict_types=1);

/**
 * @license  http://opensource.org/licenses/mit-license.php MIT
 * @link     https://github.com/nicoSWD
 * @author   Nicolas Oelgart <nico@oelgart.com>
 */
namespace App\Tests\unit\Domain\Event;

use App\Domain\Event\Event;
use App\Domain\Event\EventFilter;
use App\Domain\Filter\Filter;
use App\Domain\Round\Category;
use App\Domain\Round\Discipline;
use App\Domain\Round\Round;
use App\Domain\Round\RoundKind;
use App\Domain\Round\RoundScheduleStatus;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class EventFilterTest extends TestCase
{
    private EventFilter $eventFilter;

    #[Test] public function event_should_have_unconfirmed_rounds_if_schedule_is_provisional(): void
    {
        $event = $this->createEventWithRounds(
            $this->createRoundOfKind(RoundKind::FINAL, andStatus: RoundScheduleStatus::PROVISIONAL),
            $this->createRoundOfKind(RoundKind::SEMI_FINAL, andStatus: RoundScheduleStatus::CONFIRMED),
        );

        $filtered = $this->filterEvents($event);

        $this->assertCount(1, $filtered);
        $this->assertCount(1, $filtered[0]->rounds);
        $this->assertTrue($filtered[0]->hasUnconfirmedRounds);
    }

    #[Test] public function event_should_have_unconfirmed_rounds_if_schedule_is_provisional2(): void
    {
        $event = $this->createEventWithRounds(
            $this->createRoundOfKind(RoundKind::QUALIFICATION, andStatus: RoundScheduleStatus::PROVISIONAL),
            $this->createRoundOfKind(RoundKind::QUALIFICATION, andStatus: RoundScheduleStatus::PROVISIONAL),
        );

        $filtered = $this->filterEvents($event);

        $this->assertCount(1, $filtered);
        $this->assertCount(0, $filtered[0]->rounds);
        $this->assertTrue($filtered[0]->hasUnconfirmedRounds);
        $this->assertEmpty($filtered[0]->rounds);
    }

    #[Test] public function event_should_not_have_unconfirmed_rounds_if_schedule_is_not_provisional(): void
    {
        $event = $this->createEventWithRounds(
            $this->createRoundOfKind(RoundKind::SEMI_FINAL, andStatus: RoundScheduleStatus::CONFIRMED),
            $this->createRoundOfKind(RoundKind::FINAL, andStatus: RoundScheduleStatus::CONFIRMED),
        );

        $filtered = $this->filterEvents($event);

        $this->assertCount(1, $filtered);
        $this->assertCount(1, $filtered[0]->rounds);
        $this->assertFalse($filtered[0]->hasUnconfirmedRounds);
    }

    private function createRoundOfKind(RoundKind $kind, RoundScheduleStatus $andStatus): Round
    {
        $round = new Round();
        $round->scheduleStatus = $andStatus;
        $round->disciplines = [Discipline::BOULDER];
        $round->kind = $kind;
        $round->categories = [Category::WOMEN];

        return $round;
    }

    private function createEventWithRounds(Round ...$round): Event
    {
        $event = new Event();
        $event->rounds = $round;

        return $event;
    }

    private function buildFilter(): Filter
    {
        $filter = new Filter();
        $filter->categories = [Category::WOMEN];
        $filter->disciplines = [Discipline::BOULDER];
        $filter->rounds = [RoundKind::FINAL];

        return $filter;
    }

    /** @return Event[] */
    private function filterEvents(Event ...$events): array
    {
        return $this->eventFilter->filterEvents($events, $this->buildFilter());
    }

    protected function setUp(): void
    {
        $this->eventFilter = new EventFilter();
    }
}
