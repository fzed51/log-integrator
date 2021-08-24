<?php
declare(strict_types=1);

namespace Handlers\Settings;

return
    /**
     * @param array<string,mixed> $customSettings
     * @return array<string,mixed>
     */
    static function (array $customSettings = []): array {
        $mode = strtolower(substr($customSettings['mode'] ?? 'p', 0, 1));
        $prod = $mode === 'p';
        $defaultSettings = [
            'displayErrorDetails' => false,
            'addContentLengthHeader' => false,
            'determineRouteBeforeAppMiddleware' => false,
            'outputBuffering' => $prod,
            'routerCacheFile' => $prod,
        ];
        return ['settings' => array_merge(
            $defaultSettings,
            $customSettings
        )];
    };
