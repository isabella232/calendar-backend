<?php

/**
 * @license  http://opensource.org/licenses/mit-license.php MIT
 * @link     https://github.com/nicoSWD
 * @author   Nicolas Oelgart <nico@oelgart.com>
 */
namespace App\Domain\Calendar\Update;

use App\Domain\Calendar\Update\Exception\CalendarStorageException;

interface CalendarStorage
{
    /** @throws CalendarStorageException */
    public function persist(string $calendar): void;
}
