<?php

namespace Plisio\PlisioGateway\Lib;

use Plisio\PlisioGateway\Lib\APIError\CredentialsMissing;
use Plisio\PlisioGateway\Lib\APIError\BadEnvironment;
use Plisio\PlisioGateway\Lib\APIError\BadRequest;
use Plisio\PlisioGateway\Lib\APIError\BadCredentials;
use Plisio\PlisioGateway\Lib\APIError\BadAuthToken;
use Plisio\PlisioGateway\Lib\APIError\AccountBlocked;
use Plisio\PlisioGateway\Lib\APIError\IpAddressIsNotAllowed;
use Plisio\PlisioGateway\Lib\APIError\Unauthorized;
use Plisio\PlisioGateway\Lib\APIError\PageNotFound;
use Plisio\PlisioGateway\Lib\APIError\RecordNotFound;
use Plisio\PlisioGateway\Lib\APIError\OrderNotFound;
use Plisio\PlisioGateway\Lib\APIError\NotFound;
use Plisio\PlisioGateway\Lib\APIError\OrderIsNotValid;
use Plisio\PlisioGateway\Lib\APIError\UnprocessableEntity;
use Plisio\PlisioGateway\Lib\APIError\RateLimitException;
use Plisio\PlisioGateway\Lib\APIError\InternalServerError;
use Plisio\PlisioGateway\Lib\APIError\APIError;

class Exception
{
    public static function formatError($error)
    {
        if (is_array($error)) {
            $reason = '';
            $message = '';

            if (isset($error['reason'])) {
                $reason = $error['reason'];
            }

            if (isset($error['message'])) {
                $message = $error['message'];
            }

            return "{$reason} {$message}";
        } else {
            return $error;
        }
    }

    public static function throwException($http_status, $error)
    {
        $reason = is_array($error) && isset($error['reason']) ? $error['reason'] : '';

        switch ($http_status) {
            case 400:
                switch ($reason) {
                    case 'CredentialsMissing':
                        throw new CredentialsMissing(self::formatError($error));
                    case 'BadEnvironment':
                        throw new BadEnvironment(self::formatError($error));
                    default:
                        throw new BadRequest(self::formatError($error));
                }
            //no break
            case 401:
                switch ($reason) {
                    case 'BadCredentials':
                        throw new BadCredentials(self::formatError($error));
                    case 'BadAuthToken':
                        throw new BadAuthToken(self::formatError($error));
                    case 'AccountBlocked':
                        throw new AccountBlocked(self::formatError($error));
                    case 'IpAddressIsNotAllowed':
                        throw new IpAddressIsNotAllowed(self::formatError($error));
                    default:
                        throw new Unauthorized(self::formatError($error));
                }
            //no break
            case 404:
                switch ($reason) {
                    case 'PageNotFound':
                        throw new PageNotFound(self::formatError($error));
                    case 'RecordNotFound':
                        throw new RecordNotFound(self::formatError($error));
                    case 'OrderNotFound':
                        throw new OrderNotFound(self::formatError($error));
                    default:
                        throw new NotFound(self::formatError($error));
                }
            //no break
            case 422:
                switch ($reason) {
                    case 'OrderIsNotValid':
                        throw new OrderIsNotValid(self::formatError($error));
                    default:
                        throw new UnprocessableEntity(self::formatError($error));
                }
            //no break
            case 429:
                throw new RateLimitException(self::formatError($error));
            case 500:
                throw new InternalServerError(self::formatError($error));
            case 504:
                throw new InternalServerError(self::formatError($error));
            default:
                throw new APIError(self::formatError($error));
        }
    }
}

