<?php

namespace ride\application\media\validator;

use ride\application\validation\validator\DependencyValidator;

use ride\library\dependency\DependencyInjector;
use ride\library\media\validator\VideoValidator as LibVideoValidator;

/**
 * Validator to check if a value is a reference to video
 */
class VideoValidator extends LibVideoValidator implements DependencyValidator {

    /**
     * Hook to process a created validator
     * @param ride\library\dependency\DependencyInjector $dependencyInjector
     * @return null
     */
    public function processValidator(DependencyInjector $dependencyInjector) {
        $this->setMediaFactory($dependencyInjector->get('ride\\library\\media\\MediaFactory'));
    }

}
