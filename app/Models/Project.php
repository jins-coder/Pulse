<?php

declare(strict_types=1);

namespace App\Models;

use Pulse\Database\Model;

class Project extends Model
{
    protected string $table = 'projects';
    protected bool $isMultiTenant = true;
}
