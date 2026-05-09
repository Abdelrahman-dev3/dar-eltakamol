<?php

namespace App\Support;

use App\Models\User;

class RoutePermissionMap
{
    private const OPEN_ROUTES = [
        'dashboard',
        'home.goals-chart',
        'home.user-profit',
        'profile',
        'profile.update',
        'profile.password',
    ];

    /**
     * @return array<string, array<int, string>>
     */
    private static function customPermissions(): array
    {
        return [
            'contributors.documents.download' => self::buildPermissionCandidates('contributors.documents', 'download'),
            'contributors.documents.delete' => self::buildPermissionCandidates('contributors.documents', 'delete'),
            'contributors.statement' => self::buildPermissionCandidates('contributors', 'view'),
            'contributor.dashboard' => [],
            'contributor.statement' => [],
            'contributor.sell-offers' => [],
            'contributor.sell-offers.create' => [],
            'contributor.sell-offers.store' => [],
            'contributor.sell-offers.edit' => [],
            'contributor.sell-offers.update' => [],
            'contributor.purchase-orders' => [],
            'contributor.purchase-orders.create' => [],
            'contributor.purchase-orders.store' => [],
            'contributor.purchase-orders.independent.store' => [],
            'contributor.polls' => [],
            'contributor.polls.show' => [],
            'contributor.polls.vote' => [],
            'contributor.meetings' => [],
            'contributor-movements.index' => [],
            'contributor-movements.create' => [],
            'contributor-movements.store' => [],
            'meetings.attachments.download' => self::buildPermissionCandidates('meetings.attachments', 'download'),
            'meetings.attachments.delete' => self::buildPermissionCandidates('meetings.attachments', 'delete'),
            'shares-trans.post' => self::buildPermissionCandidates('shares-trans', 'post'),
            'polls.vote' => self::buildPermissionCandidates('polls', 'vote'),
            'polls.results' => self::buildPermissionCandidates('polls', 'results'),
            'users.profits' => self::buildPermissionCandidates('users.profits', 'view'),
            'users-profits.mark-paid' => self::buildPermissionCandidates('users-profits', 'mark-paid'),
            'profits.toggle-active' => self::buildPermissionCandidates('profits', 'toggle-active'),
            'share-trans-lines.toggle-posted' => self::buildPermissionCandidates('share-trans-lines', 'toggle-posted'),
            'payments.toggle-confirmed' => self::buildPermissionCandidates('payments', 'toggle-confirmed'),
            'shares-pos.toggle-accept' => self::buildPermissionCandidates('shares-pos', 'toggle-accept'),
            'shares-pos.mark-default' => self::buildPermissionCandidates('shares-pos', 'update'),
            'sell-shares.settle' => self::buildPermissionCandidates('sell-shares', 'edit'),
            'sell-shares.close' => self::buildPermissionCandidates('sell-shares', 'edit'),
            'trading-periods.index' => ['settings.view', 'settings.edit'],
            'trading-periods.create' => ['settings.edit'],
            'trading-periods.store' => ['settings.edit'],
            'trading-periods.edit' => ['settings.edit'],
            'trading-periods.update' => ['settings.edit'],
            'trading-periods.destroy' => ['settings.edit'],
            'company-purchase-obligations.index' => ['sell-shares.view', 'sell-shares.edit'],
            'company-purchase-obligations.show' => ['sell-shares.view', 'sell-shares.edit'],
            'company-purchase-obligations.edit' => ['sell-shares.edit'],
            'company-purchase-obligations.update' => ['sell-shares.edit'],
            'independent-purchase-orders.index' => ['shares-pos.view', 'shares.pos.view', 'transactions.view'],
            'independent-purchase-orders.show' => ['shares-pos.view', 'shares.pos.view', 'transactions.view'],
            'independent-purchase-orders.update' => ['shares-pos.edit', 'shares.pos.edit', 'transactions.edit'],
            'bookings.update-status' => self::buildPermissionCandidates('bookings', 'update'),
            'contributors.share' => self::buildPermissionCandidates('sell-shares', 'create'),
        ];
    }

    /**
     * @return array<int, string>
     */
    public static function permissionsForRoute(?string $routeName): array
    {
        if (!$routeName || in_array($routeName, self::OPEN_ROUTES, true)) {
            return [];
        }

        $customPermissions = self::customPermissions();

        if (array_key_exists($routeName, $customPermissions)) {
            return $customPermissions[$routeName];
        }

        $segments = array_values(array_filter(explode('.', $routeName)));

        if (count($segments) < 2) {
            return [];
        }

        $action = array_pop($segments);
        $resource = implode('.', $segments);

        return self::buildPermissionCandidates($resource, $action);
    }

    public static function userCanAccess(?User $user, string|array ...$routeNames): bool
    {
        $routeNames = self::flattenRouteNames($routeNames);

        if (empty($routeNames)) {
            return true;
        }

        foreach ($routeNames as $routeName) {
            $permissions = self::permissionsForRoute($routeName);

            if (empty($permissions)) {
                return true;
            }

            if ($user && $user->hasAnyPermission($permissions)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param  array<int, string|array>  $routeNames
     * @return array<int, string>
     */
    private static function flattenRouteNames(array $routeNames): array
    {
        $flattened = [];

        array_walk_recursive($routeNames, function ($routeName) use (&$flattened): void {
            if (is_string($routeName) && $routeName !== '') {
                $flattened[] = $routeName;
            }
        });

        return array_values(array_unique($flattened));
    }

    /**
     * @return array<int, string>
     */
    private static function buildPermissionCandidates(string $resource, string $action): array
    {
        $permissions = [];

        foreach (self::resourceAliases($resource) as $resourceAlias) {
            $permissions[] = $resourceAlias;

            foreach (self::actionAliases($action) as $actionAlias) {
                $permissions[] = $resourceAlias . '.' . $actionAlias;
            }

            $permissions[] = $resourceAlias . '.manage';
        }

        return array_values(array_unique($permissions));
    }

    /**
     * @return array<int, string>
     */
    private static function resourceAliases(string $resource): array
    {
        $aliases = [$resource];

        $map = [
            'servies' => ['services'],
            'poll-options' => ['poll.options', 'polls'],
            'poll-answers' => ['poll.answers', 'polls'],
            'sell-shares' => ['sell.shares', 'shares'],
            'shares-trans' => ['shares.trans', 'transactions'],
            'share-trans-lines' => ['share.trans.lines', 'transactions'],
            'shares-pos' => ['shares.pos', 'transactions'],
            'payments' => ['transactions'],
            'modify' => ['transactions'],
            'users-profits' => ['users.profits', 'profits'],
            'users.profits' => ['users-profits', 'profits'],
            'contributors.documents' => ['documents'],
            'meetings.attachments' => ['documents'],
            'trading-periods' => ['settings'],
            'company-purchase-obligations' => ['sell-shares', 'transactions'],
        ];

        if (array_key_exists($resource, $map)) {
            $aliases = array_merge($aliases, $map[$resource]);
        }

        $dottedAlias = str_replace('-', '.', $resource);

        if ($dottedAlias !== $resource) {
            $aliases[] = $dottedAlias;
        }

        return array_values(array_unique(array_filter($aliases)));
    }

    /**
     * @return array<int, string>
     */
    private static function actionAliases(string $action): array
    {
        return match ($action) {
            'index', 'show' => ['view', 'index', 'show'],
            'create', 'store' => ['create', 'store'],
            'edit', 'update' => ['edit', 'update'],
            'destroy', 'delete' => ['delete', 'destroy'],
            'download' => ['download', 'view'],
            'vote' => ['vote', 'view'],
            'results' => ['results', 'view'],
            'post' => ['post', 'update', 'edit'],
            'mark-paid' => ['mark', 'paid', 'update', 'edit'],
            'toggle-active', 'toggle-posted', 'toggle-confirmed', 'toggle-accept' => ['toggle', 'update', 'edit'],
            default => [$action],
        };
    }
}
