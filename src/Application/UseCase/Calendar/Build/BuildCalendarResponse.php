<?php declare(strict_types=1);

/**
 * @license  http://opensource.org/licenses/mit-license.php MIT
 * @link     https://github.com/nicoSWD
 * @author   Nicolas Oelgart <nico@oelgart.com>
 */
namespace App\Application\UseCase\Calendar\Build;

final readonly class BuildCalendarResponse
{
    public function __construct(
        public string $calendar,
    ) {
    }
}
