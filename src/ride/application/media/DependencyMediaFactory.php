<?php

namespace ride\application\media;

use ride\library\dependency\DependencyInjector;
use ride\library\http\client\Client;
use ride\library\media\exception\MediaException;
use ride\library\media\MediaFactory;

/**
 * Simple media factory
 */
class DependencyMediaFactory implements MediaFactory {

    /**
     * Instance of the dependency injector
     * @var \ride\library\dependency\DependencyInjector
     */
    protected $dependencyInjector;

    /**
     * Constructs a new media factory
     * @param \ride\library\dependency\DependencyInjector
     * @return null
     */
    public function __construct(DependencyInjector $dependencyInjector) {
        $this->dependencyInjector = $dependencyInjector;
    }

    /**
     * Gets the HTTP client used by the media library
     * @return \ride\library\http\client\Client
     */
    public function getHttpClient() {
        return $this->dependencyInjector->get('ride\\library\\http\\client\\Client');
    }

    /**
     * Creates a media item from a URL
     * @param string $url URL to a item of a media service
     * @return \ride\library\media\item\MediaItem Instance of the media item
     * @throws \ride\library\media\exception\MediaException when no media item
     * instance could be created
     */
    public function createMediaItem($url) {
        $arguments = array(
            'id' => null,
            'url' => $url,
        );

        if (stripos($url, 'youtu') !== false) {
            $mediaItem = $this->dependencyInjector->get('ride\\library\\media\\item\\MediaItem', 'youtube', $arguments, true);
        } elseif (stripos($url, 'vimeo') !== false) {
            $mediaItem = $this->dependencyInjector->get('ride\\library\\media\\item\\MediaItem', 'vimeo', $arguments, true);
        } elseif (stripos($url, 'soundcloud') !== false) {
            $mediaItem = $this->dependencyInjector->get('ride\\library\\media\\item\\MediaItem', 'soundcloud', $arguments, true);
        } else {
            throw new MediaException('Could not create a media item for ' . $url . ': unsupported type or invalid URL provided');
        }

        return $mediaItem;
    }

    /**
     * Gets a media item by it's type and id
     * @param string type
     * @param string id
     * @return \ride\library\media\item\MediaItem
     */
    public function getMediaItem($type, $id) {
        $arguments = array(
            'id' => $id,
        );

        try {
            $mediaItem = $this->dependencyInjector->get('ride\\library\\media\\item\\MediaItem', $type, $arguments, true);
        } catch (DependencyException $exception) {
            throw new VideoException('Could not get the media item', 0, $exception);
        }

        return $mediaItem;
    }

}
