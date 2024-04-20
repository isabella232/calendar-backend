<?php declare(strict_types=1);

/**
 * @license  http://opensource.org/licenses/mit-license.php MIT
 * @link     https://github.com/nicoSWD
 * @author   Nicolas Oelgart <nico@oelgart.com>
 */
namespace App\Domain\Round;

enum RoundScheduleStatus: string
{
    case ESTIMATED = 'estimated';
    case PROVISIONAL = 'provisional';
    case CONFIRMED = 'confirmed';

    public function isConfirmed(): bool
    {
        return $this === self::CONFIRMED;
    }

    public function isProvisional(): bool
    {
        return $this === self::PROVISIONAL;
    }
}
