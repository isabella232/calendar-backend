<?php declare(strict_types=1);

/**
 * @license  http://opensource.org/licenses/mit-license.php MIT
 * @link     https://github.com/nicoSWD
 * @author   Nicolas Oelgart <nico@oelgart.com>
 */
namespace App\Domain\Round;

enum RoundKind: string
{
    case QUALIFICATION = 'qualification';
    case SEMI_FINAL = 'semi-final';
    case FINAL = 'final';

    public function isQualification(): bool
    {
        return $this === self::QUALIFICATION;
    }
}
