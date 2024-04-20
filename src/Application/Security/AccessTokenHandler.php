<?php declare(strict_types=1);

namespace App\Application\Security;

/**
 * @license  http://opensource.org/licenses/mit-license.php MIT
 * @link     https://github.com/nicoSWD
 * @author   Nicolas Oelgart <nico@oelgart.com>
 */
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Http\AccessToken\AccessTokenHandlerInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;

final readonly class AccessTokenHandler implements AccessTokenHandlerInterface
{
    private const string USER_ADMIN = 'admin';

    public function __construct(
        private string $accessToken,
    ) {
    }

    public function getUserBadgeFrom(string $accessToken): UserBadge
    {
        if (!hash_equals($this->accessToken, $accessToken)) {
            throw new BadCredentialsException('Invalid access token.');
        }

        return new UserBadge(self::USER_ADMIN);
    }
}
