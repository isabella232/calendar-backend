<?php declare(strict_types=1);

/**
 * @license  http://opensource.org/licenses/mit-license.php MIT
 * @link     https://github.com/nicoSWD
 * @author   Nicolas Oelgart <nico@oelgart.com>
 */
namespace App\Domain\Round;

enum Discipline: string
{
    case BOULDER = 'boulder';
    case LEAD = 'lead';
    case SPEED = 'speed';
}
