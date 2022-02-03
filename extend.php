<?php

/*
 * This file is part of acpl/flarum-post-validator.
 *
 * Copyright (c) 2022 forum.android.com.pl.
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace ACPL\PostValidator;

use ACPL\PostValidator\Console\ValidatePosts;
use Flarum\Extend;

return [
    (new Extend\Console())
        ->command(ValidatePosts::class)
];
