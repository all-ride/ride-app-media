# ride/app-media

This is the app implementation of ``ride/lib-media``. The main interface if the ``DependencyMediaFactory`` which does the same as ``ride\library\media\SimpleMediaFactory`` except for the use of registered dependencies on media item factories. Read the readme of ``ride/lib-media`` for further reference.

### Code reference

Instead of manually adding MediaItem factories in the ``createMediaItem`` method, you can add them as a dependency, these can either be simple dependencies like eg. for the VimeoMediaItemFactory:

```js
// ride/app-media/config/dependencies.json
{
    "dependencies": [
        // ...
        {
            "interfaces": "ride\\library\\media\\factory\\MediaItemFactory",
            "class": "ride\\library\\media\\factory\\VimeoMediaItemFactory",
            "id": "mediaitem.factory.vimeo"
        }
        // ...
    ]
}
```

or you could add the clientId parameter, like eg. for the YoutubeMediaItemFactory:
```js
// ride/app-media/config/dependencies.json
{
    "dependencies": [
        // ...
        {
            "interfaces": "ride\\library\\media\\factory\\MediaItemFactory",
            "class": "ride\\library\\media\\factory\\YoutubeMediaItemFactory",
            "id": "mediaitem.factory.youtube",
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

### Code sample

```php
use ride\application\media\DependencyMediaFactory;
use ride\library\http\client\Client;

$httpClient = // get http Client;
$dependencyMediaFactory = new DependencyMediaFactory($httpClient);

// create a MediaItem (eg. Vimeo)
$vimeoMediaItem = $dependencyMediaFactory->createMediaItem('https://vimeo.com/130848841');

// create a MediaItem which depends on a clientId, but is injected via the DependencyInjector (eg. Youtube)
$youtubeMediaItem = $dependencyMediaFactory->createMediaItem('https://www.youtube.com/watch?v=njos57IJf-0');
```
