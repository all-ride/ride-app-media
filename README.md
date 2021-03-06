# Ride: Media Implementation

This is the implementation of ``ride/lib-media`` in the Ride application layer.
 
The main interface if the ``DependencyMediaFactory`` which does the same as ``ride\library\media\SimpleMediaFactory`` except for the use of registered dependencies on media item factories. 
Check the README of ``ride/lib-media`` for further reference.

## Parameters

* __google.api.key__: API key for the google related api calls (Youtube, Geocode...)

## Code reference

Instead of manually adding MediaItem factories in the ``createMediaItem`` method, you can add them as a dependency, these can either be simple dependencies like eg. for the VimeoMediaItemFactory:

```json
// ride/app-media/config/dependencies.json
{
    "dependencies": [
        // ...
        {
            "interfaces": "ride\\library\\media\\factory\\MediaItemFactory",
            "class": "ride\\library\\media\\factory\\VimeoMediaItemFactory",
            "id": "vimeo"
        }
        // ...
    ]
}
```

or you could add the clientId parameter, like eg. for the YoutubeMediaItemFactory:

```json
// ride/app-media/config/dependencies.json
{
    "dependencies": [
        // ...
        {
            "interfaces": "ride\\library\\media\\factory\\MediaItemFactory",
            "class": "ride\\library\\media\\factory\\YoutubeMediaItemFactory",
            "id": "youtube",
            "calls": [
                {
                    "method": "setClientId",
                    "arguments": [
                        {
                            "name": "clientId",
                            "type": "parameter",
                            "properties": {
                                "key": "google.api.key"
                            }
                        }
                    ]
                }
            ]
        }
        // ...
    ]
}
```

## Code sample

```php
<?php

use ride\application\media\DependencyMediaFactory;

use ride\library\dependency\DependencyInjector;

function testMediaFactory(DependencyInjector $dependencyInjector) {
    $dependencyMediaFactory = new DependencyMediaFactory($dependencyInjector);

    // create a MediaItem (eg. Vimeo)
    $vimeoMediaItem = $dependencyMediaFactory->createMediaItem('https://vimeo.com/130848841');

    // create a MediaItem which depends on a clientId, but is injected via the DependencyInjector (eg. Youtube)
    $youtubeMediaItem = $dependencyMediaFactory->createMediaItem('https://www.youtube.com/watch?v=njos57IJf-0');

    // ```
}
```

## Related Modules 

- [ride/app](https://github.com/all-ride/ride-app)
- [ride/app-orm-asset](https://github.com/all-ride/ride-app-orm-asset)
- [ride/app-validation](https://github.com/all-ride/ride-app-validation)
- [ride/lib-dependency](https://github.com/all-ride/ride-lib-dependency)
- [ride/lib-media](https://github.com/all-ride/ride-lib-media)
- [ride/lib-validation](https://github.com/all-ride/ride-lib-validation)

## Installation

You can use [Composer](http://getcomposer.org) to install this module.

```
composer require ride/app-media
```

