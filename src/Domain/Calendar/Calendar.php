<?php declare(strict_types=1);

/**
 * @license  http://opensource.org/licenses/mit-license.php MIT
 * @link     https://github.com/nicoSWD
 * @author   Nicolas Oelgart <nico@oelgart.com>
 */
namespace App\Domain\Calendar;

use App\Domain\Event\Event;

final class Calendar
{
    /** @var Event[] */
    public array $events;
}
