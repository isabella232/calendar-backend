<?php declare(strict_types=1);

/**
 * @license  http://opensource.org/licenses/mit-license.php MIT
 * @link     https://github.com/nicoSWD
 * @author   Nicolas Oelgart <nico@oelgart.com>
 */
namespace App\Domain\Filter;

use App\Domain\Round\Category;
use App\Domain\Round\Discipline;
use App\Domain\Round\RoundKind;

final class Filter
{
    /** @var Category[] */
    public array $categories;

    /** @var RoundKind[] */
    public array $rounds;

    /** @var Discipline[] */
    public array $disciplines;
}
