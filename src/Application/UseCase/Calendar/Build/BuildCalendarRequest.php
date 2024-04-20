<?php declare(strict_types=1);

/**
 * @license  http://opensource.org/licenses/mit-license.php MIT
 * @link     https://github.com/nicoSWD
 * @author   Nicolas Oelgart <nico@oelgart.com>
 */
namespace App\Application\UseCase\Calendar\Build;

use App\Domain\Filter\Filter;

final readonly class BuildCalendarRequest
{
    public function __construct(
        public Filter $filter,
    ) {
    }
}
