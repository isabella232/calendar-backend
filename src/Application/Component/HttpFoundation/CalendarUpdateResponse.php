<?php declare(strict_types=1);

/**
 * @license  http://opensource.org/licenses/mit-license.php MIT
 * @link     https://github.com/nicoSWD
 * @author   Nicolas Oelgart <nico@oelgart.com>
 */
namespace App\Application\Component\HttpFoundation;

use Symfony\Component\HttpFoundation\JsonResponse;

final class CalendarUpdateResponse extends JsonResponse
{
    public function __construct(string $status, ?string $errorMessage)
    {
        $this->encodingOptions |= JSON_PRETTY_PRINT;

        parent::__construct([
            'status' => $status,
            'error' => $errorMessage,
        ]);
    }
}
