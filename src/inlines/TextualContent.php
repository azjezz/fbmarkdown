<?hh // strict
/*
 *  Copyright (c) 2004-present, Facebook, Inc.
 *  All rights reserved.
 *
 *  This source code is licensed under the MIT license found in the
 *  LICENSE file in the root directory of this source tree.
 *
 */

namespace Facebook\Markdown\Inlines;

use namespace HH\Lib\{C, Str};

class TextualContent extends InlineWithPlainTextContent {
  <<__Override>>
  public static function consume(
    Context $context,
    string $input,
    int $offset,
  ): (Inline, int) {
    $out = $input[$offset];
    $len = Str\length($input);

    for ($i = $offset + 1; $i < $len; ++$i) {
      list($inlines, $_) = _Private\parse_with_denylist(
        $context,
        $input,
        $i,
        /* denylist = */ keyset[self::class],
      );
      if (!C\is_empty($inlines)) {
        break;
      }
      $out .= $input[$i];
    }

    return tuple(
      new self($out),
      $i,
    );
  }
}
