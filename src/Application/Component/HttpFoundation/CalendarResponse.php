<?php declare(strict_types=1);

/**
 * @license  http://opensource.org/licenses/mit-license.php MIT
 * @link     https://github.com/nicoSWD
 * @author   Nicolas Oelgart <nico@oelgart.com>
 */
namespace App\Application\Component\HttpFoundation;

use Symfony\Component\HttpFoundation\Response;

final class CalendarResponse extends Response
{
    public function __construct(string $content)
    {
        parent::__construct($content, headers: [
            'Content-Type' => 'text/calendar',
        ]);
    }
}
