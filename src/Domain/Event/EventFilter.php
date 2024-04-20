<?php declare(strict_types=1);

/**
 * @license  http://opensource.org/licenses/mit-license.php MIT
 * @link     https://github.com/nicoSWD
 * @author   Nicolas Oelgart <nico@oelgart.com>
 */
namespace App\Domain\Event;

use App\Domain\Filter\Filter;
use App\Domain\Round\Round;
use StringBackedEnum;

final readonly class EventFilter
{
    /**
     * @param Event[] $events
     * @return Event[]
     */
    public function filterEvents(array $events, Filter $filter): array
    {
        $filteredEvents = [];

        foreach ($events as $event) {
            $event->hasUnconfirmedRounds = $this->eventHasUnconfirmedRounds($event);
            $filteredRounds = [];

            foreach ($event->rounds as $round) {
                if ($this->roundMatchesFilter($round, $filter)) {
                    $filteredRounds[] = $round;
                }
            }

            if (!empty($filteredRounds) || $event->hasUnconfirmedRounds) {
                $event->rounds = $filteredRounds;
                $filteredEvents[] = $event;
            }
        }

        return $filteredEvents;
    }

    private function eventHasUnconfirmedRounds(Event $event): bool
    {
        $onlyHasQualificationRounds = true;

        foreach ($event->rounds as $round) {
            if (!$round->kind->isQualification()) {
                $onlyHasQualificationRounds = false;

                if (!$round->scheduleStatus->isConfirmed()) {
                    return true;
                }
            }
        }

        return $onlyHasQualificationRounds;
    }

    private function roundMatchesFilter(Round $round, Filter $filter): bool
    {
        return
            $this->hasMatchingCategory($round, $filter) &&
            $this->hasMatchingDiscipline($round, $filter) &&
            $this->hasMatchingRoundKind($round, $filter);
    }

    private function hasMatchingCategory(Round $round, Filter $filter): bool
    {
        return $this->filterMatches($round->categories, $filter->categories);
    }

    private function hasMatchingDiscipline(Round $round, Filter $filter): bool
    {
        return $this->filterMatches($round->disciplines, $filter->disciplines);
    }

    private function hasMatchingRoundKind(Round $round, Filter $filter): bool
    {
        return in_array($round->kind, $filter->rounds, strict: true);
    }

    /**
     * @param StringBackedEnum[] $eventDisciplines
     * @param StringBackedEnum[] $filterDisciplines
     */
    private function filterMatches(array $eventDisciplines, array $filterDisciplines): bool
    {
        foreach ($eventDisciplines as $discipline) {
            if (in_array($discipline, $filterDisciplines, strict: true)) {
                return true;
            }
        }

        return false;
    }
}
