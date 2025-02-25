<?php declare(strict_types=1);

/**
 * @license  http://opensource.org/licenses/mit-license.php MIT
 * @link     https://github.com/nicoSWD
 * @author   Nicolas Oelgart <nico@oelgart.com>
 */
namespace App\Domain\Calendar\Update;

final readonly class CalendarUpdateResult
{
    public function __construct(
        public CalendarUpdateStatus $status,
        public ?string $errorMessage = null,
    ) {
    }
}
