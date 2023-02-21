<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class ExpectsActAs
{
    public const HEADER_KEY = 'Tagd-Act-As';

    public const HEADER_SEPARATOR = ':';

    public static function extract(Request $request): array
    {
        $key = static::HEADER_KEY;

        try {
            $values = explode(
                static::HEADER_SEPARATOR,
                $request->headers->get($key)
            );

            [$actorName, $actorId] = $values;

            return [
                'name' => trim(strtolower($actorName)),
                'id' => trim(strtolower($actorId)),
            ];
        } catch (\Exception $e) {
            throw new BadRequestHttpException("Invalid $key header");
        }
    }

    public static function parse(Request $request): ?array
    {
        $key = static::HEADER_KEY;

        if ($request->headers->has($key)) {
            $actAs = static::extract($request);

            throw_if(
                ! in_array($actAs['name'], ['retailer', 'reseller', 'consumer']),
                new BadRequestHttpException("Invalid $key header")
            );

            throw_if(
                '' == $actAs['id'],
                new BadRequestHttpException("Invalid $key header")
            );

            return $actAs;
        } else {
            return null;
        }
    }

    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        static::parse($request);

        return $next($request);
    }
}
