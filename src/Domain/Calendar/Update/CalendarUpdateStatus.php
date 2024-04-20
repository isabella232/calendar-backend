<?php declare(strict_types=1);

/**
 * @license  http://opensource.org/licenses/mit-license.php MIT
 * @link     https://github.com/nicoSWD
 * @author   Nicolas Oelgart <nico@oelgart.com>
 */
namespace App\Domain\Calendar\Update;

enum CalendarUpdateStatus: string
{
    case UPDATE_SUCCESS = 'success';
    case UPDATE_INCOMPATIBLE = 'incompatible';
    case UPDATE_FAILURE = 'failure';
    case DOWNLOAD_FAILED = 'download_failed';
}
