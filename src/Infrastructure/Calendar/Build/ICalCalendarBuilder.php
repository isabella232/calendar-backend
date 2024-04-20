<?php declare(strict_types=1);

/**
 * @license  http://opensource.org/licenses/mit-license.php MIT
 * @link     https://github.com/nicoSWD
 * @author   Nicolas Oelgart <nico@oelgart.com>
 */
namespace App\Infrastructure\Calendar\Build;

use App\Domain\Calendar\Build\CalendarBuilderInterface;
use App\Domain\Event\Event;
use App\Domain\Event\EventFilter;
use App\Domain\Filter\Filter;
use App\Domain\Round\Round;
use DateInterval;
use DateTimeInterface;
use DateTimeZone;
use Eluceo\iCal\Domain\Entity\Calendar;
use Eluceo\iCal\Domain\Entity\Event as CalendarEvent;
use Eluceo\iCal\Domain\Enum\EventStatus;
use Eluceo\iCal\Domain\ValueObject\Alarm;
use Eluceo\iCal\Domain\ValueObject\Alarm\DisplayAction;
use Eluceo\iCal\Domain\ValueObject\Alarm\RelativeTrigger;
use Eluceo\iCal\Domain\ValueObject\DateTime as CalendarDateTime;
use Eluceo\iCal\Domain\ValueObject\Location;
use Eluceo\iCal\Domain\ValueObject\TimeSpan;
use Eluceo\iCal\Domain\ValueObject\Uri;
use Eluceo\iCal\Presentation\Factory\CalendarFactory;
use Exception;
use Override;

final readonly class ICalCalendarBuilder implements CalendarBuilderInterface
{
    private const string DISCORD_URL = 'https://discord.gg/rbM5vjcVHM';

    public function __construct(
        private CalendarFactory $calendarFactory,
        private EventFilter $eventFilter,
        private string $productIdentifier,
        private string $publishedTtl,
    ) {
    }

    /**
     * @inheritdoc
     * @throws Exception
     */
    #[Override] public function generateForEvents(array $events, Filter $filter): string
    {
        $events = $this->eventFilter->filterEvents($events, $filter);

        return (string) $this->calendarFactory->createCalendar(
            $this->createCalenderFromEvents($events),
        );
    }

    /**
     * @param Event[] $events
     * @throws Exception
     */
    private function createCalenderFromEvents(array $events): Calendar
    {
        $calendar = new Calendar($this->createEvents($events));
        $calendar->setProductIdentifier($this->productIdentifier);
        $calendar->setPublishedTTL(new DateInterval($this->publishedTtl));

        return $calendar;
    }

    /**
     * @param Event[] $events
     * @throws Exception
     */
    private function createEvents(array $events): array
    {
        $calendarEvents = [];

        foreach ($events as $event) {
            if (!empty($event->rounds)) {
                foreach ($event->rounds as $round) {
                    $calendarEvents[] = $this->createEvent($event, $round);
                }
            } else {
                $calendarEvents[] = $this->createEventWithoutRounds($event);
            }
        }

        return $calendarEvents;
    }

    private function createEvent(Event $event, Round $round): CalendarEvent
    {
        $calendarEvent = (new CalendarEvent())
            ->setSummary(sprintf("IFSC: %s - %s (%s)", $round->name, $event->location, $event->country))
            ->setDescription($this->buildDescription($event, $round))
            ->setUrl(new Uri($event->siteUrl))
            ->setStatus($this->getEventStatus($round))
            ->setLocation(new Location("{$event->location} ({$event->country})"))
            ->setOccurrence($this->buildTimeSpan($round->startsAt, $round->endsAt, $event->timezone));

        if ($round->scheduleStatus->isConfirmed()) {
            $calendarEvent->addAlarm(
                $this->createAlarmOneHourBefore($event, $round->name),
            );
        }

        return $calendarEvent;
    }

    /** @throws Exception */
    private function createEventWithoutRounds(Event $event): CalendarEvent
    {
        return (new CalendarEvent())
            ->setSummary(sprintf('%s (%s)', $event->name, $event->country))
            ->setDescription($this->buildDescription($event))
            ->setUrl(new Uri($event->siteUrl))
            ->setStatus(EventStatus::TENTATIVE())
            ->setLocation(new Location("{$event->location} ({$event->country})"))
            ->setOccurrence($this->buildTimeSpan($event->startsAt, $event->endsAt, $event->timezone))
            ->addAlarm($this->createAlarmOneDayBefore($event, $event->name));
    }

    private function buildTimeSpan(DateTimeInterface $startsAt, DateTimeInterface $endsAt, DateTimeZone $timeZone): TimeSpan
    {
        return new TimeSpan(
            new CalendarDateTime($startsAt->setTimezone($timeZone), applyTimeZone: true),
            new CalendarDateTime($endsAt->setTimezone($timeZone), applyTimeZone: true),
        );
    }

    private function buildDescription(Event $event, ?Round $round = null): string
    {
        $description = "🏆 {$event->name} ({$event->country})\n\n";

        if ($round?->scheduleStatus->isProvisional()) {
            $description .= "⚠️ Schedule is provisional and might change. ";
            $description .= "This calendar will update automatically once it's confirmed!\n\n";
        } elseif ($event->hasUnconfirmedRounds) {
            $description .= "⚠️ Precise schedule has not been announced yet. ";
            $description .= "This calendar will update automatically once it's published!\n\n";
        }

        $description .= "🧗 League:\n{$event->leagueName}\n\n";
        $description .= "🍿 Stream URL:\n{$event->siteUrl}\n\n";
        $description .= "💬 Join Discord:\n" . self::DISCORD_URL . "\n";

        if ($event->startList) {
            $description .= "\n📋 Start List:\n";

            foreach ($event->startList as $athlete) {
                $description .= " - {$athlete->firstName} {$athlete->lastName} ({$athlete->country})\n";
            }

            $description .= " - ...\n";
        }

        $description .= "\n☕️ If you find this useful, please consider buying me a coffee:\n";
        $description .= "https://www.buymeacoffee.com/sportclimbing\n\n";

        $description .= "🐛 Report a bug/problem:\n";
        $description .= "https://github.com/sportclimbing/ifsc-calendar/issues\n";

        return $description;
    }

    private function getEventStatus(Round $round): EventStatus
    {
        return $round->scheduleStatus->isConfirmed()
            ? EventStatus::CONFIRMED()
            : EventStatus::TENTATIVE();
    }

    private function createAlarmOneHourBefore(Event $event, string $name): Alarm
    {
        return $this->createAlarm("IFSC: {$name} - {$event->location} ({$event->country})", timeBefore: '1 hour');
    }

    private function createAlarmOneDayBefore(Event $event, string $name): Alarm
    {
        return $this->createAlarm("{$name} - {$event->leagueName}", timeBefore: '1 day');
    }

    private function createAlarm(string $name, string $timeBefore): Alarm
    {
        $trigger = new RelativeTrigger(
            DateInterval::createFromDateString(datetime: "-{$timeBefore}"),
        );

        return new Alarm(
            new DisplayAction(
                description: "Reminder: {$name} starts in {$timeBefore}!"
            ),
            $trigger->withRelationToEnd(),
        );
    }
}
