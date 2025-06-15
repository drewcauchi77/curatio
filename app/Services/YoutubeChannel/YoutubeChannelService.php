<?php

namespace App\Services\YoutubeChannel;

use App\Actions\YoutubeChannel\CreateOrUpdateYoutubeChannel;
use App\Models\User;
use App\Models\YoutubeChannel;
use App\Repositories\YoutubeChannelRepository;
use Exception;

/**
 * Main service orchestrating YouTube channel operations.
 */
class YoutubeChannelService
{
    public function __construct(
        private readonly YoutubeChannelRepository $channelRepository,
        private readonly YoutubeAuthService $authService,
        private readonly YoutubeApiService $apiService,
        private readonly CreateOrUpdateYoutubeChannel $createOrUpdateAction
    ) {}

    /**
     * Handle OAuth callback and create/update channel.
     * 
     * @param string $code
     * @param User $user
     * @return YoutubeChannel
     * @throws Exception
     */
    public function handleAuthCallback(string $code, User $user): YoutubeChannel
    {
        try {
            $codeVerifier = $this->authService->getCodeVerifier();

            if (!$codeVerifier) {
                throw new Exception('Invalid session state');
            }

            $token = $this->authService->fetchAccessToken($code, $codeVerifier);

            if (isset($token['error'])) {
                throw new Exception('OAuth error: ' . $token['error_description']);
            }

            $channelId = $this->apiService->getChannelId($token);

            return $this->createOrUpdateAction->handle($user, $channelId, $token['access_token']);
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Get connection status and channel data for a user.
     * 
     * @param User $user
     * @return array
     */
    public function getConnectionStatus(User $user): array
    {
        $channel = $this->channelRepository->findByUser($user);
        $connected = $channel && $channel->is_active;

        if (!$connected) {
            return [
                'connected' => false,
                'authUrl' => $this->authService->getAuthUrl(),
            ];
        }

        try {
            $channelData = $this->apiService->getChannelData($channel->channel_id);

            if (!$channelData) {
                return ['connected' => false, 'error' => 'Channel not found'];
            }

            return [
                'connected' => true,
                'authUrl' => null,
                'channelData' => [
                    'id' => $channel->channel_id,
                    'info' => $channelData
                ]
            ];
        } catch (\Exception $e) {
            return ['connected' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Disconnect user's YouTube channel.
     * 
     * @param User $user
     * @return bool
     */
    public function disconnect(User $user): bool
    {
        return $this->channelRepository->disconnect($user);
    }
}
