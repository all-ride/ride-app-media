<?php

namespace ride\application\media;

use ride\library\dependency\DependencyInjector;
use ride\library\http\client\Client;
use ride\library\media\exception\UnsupportedMediaException;
use ride\library\media\MediaFactory;
use ride\library\media\factory\EmbedMediaItemFactory;

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
    public function createMediaItem($url, $clientId=null) {
        $mediaItemFactories = $this->dependencyInjector->getByTag('ride\\library\\media\\factory\\MediaItemFactory');

        foreach($mediaItemFactories as $mediaItemFactory) {
            if ($mediaItemFactory->isValidUrl($url)) {
                return $mediaItemFactory->createFromUrl($url);
            }
        }

        $embedFactory = new EmbedMediaItemFactory($this->dependencyInjector);
        return $embedFactory->createFromUrl($url);
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
