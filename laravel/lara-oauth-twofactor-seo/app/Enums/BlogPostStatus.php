<?php

declare(strict_types=1);

namespace App\Enums;

enum BlogPostStatus: string
{
  case Draft = 'draft';
  case Published = 'published';
  case Archived = 'archived';

  /**
   * Get enum values as a simple list for validation.
   *
   * @return list<string>
   */
  public static function values(): array
  {
    return array_map(
      static fn(self $status): string => $status->value,
      self::cases(),
    );
  }
}
