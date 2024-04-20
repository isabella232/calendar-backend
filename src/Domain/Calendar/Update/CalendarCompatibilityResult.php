<?php declare(strict_types=1);

/**
 * @license  http://opensource.org/licenses/mit-license.php MIT
 * @link     https://github.com/nicoSWD
 * @author   Nicolas Oelgart <nico@oelgart.com>
 */
namespace App\Domain\Calendar\Update;

final readonly class CalendarCompatibilityResult
{
    public function __construct(
        public bool $isCompatible,
        public string $errorMsg,
    ) {
    }
}
