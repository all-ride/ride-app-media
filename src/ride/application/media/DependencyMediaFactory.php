<?php

namespace ride\application\media;

use ride\library\dependency\DependencyInjector;
use ride\library\http\client\Client;
use ride\library\media\exception\UnsupportedMediaException;
use ride\library\media\factory\MediaItemFactory;
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
     * Default media item factory, fallback
     * @var \ride\library\media\factory\MediaItemFactory
     */
    protected $defaultMediaItemFactory;

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
     * Sets the default media item factory, a fallback
     * @param \ride\library\media\factory\MediaItemFactory $defaultMediaItemFactory
     * @return null
     */
    public function setDefaultMediaItemFactory(MediaItemFactory $defaultMediaItemFactory) {
        $this->defaultMediaItemFactory = $defaultMediaItemFactory;
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
                kd($mediaItemFactory);
                return $mediaItemFactory->createFromUrl($url);
            }
        }

        if ($this->defaultMediaItemFactory) {
            return $this->defaultMediaItemFactory->createFromUrl($url);
        }

        throw new UnsupportedMediaException('Could not get media item for ' . $url . ': no factory available');
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
