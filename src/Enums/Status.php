<?php

namespace Thefeqy\ModelStatus\Enums;

/**
 * @deprecated since 1.2.0 and will be removed in 2.0.0. Use \Thefeqy\ModelStatus\Status instead.
 */
enum Status: string
{
    case ACTIVE = 'active';
    case INACTIVE = 'inactive';
}
