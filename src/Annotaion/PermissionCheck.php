<?php
declare(strict_types=1);
namespace Liuxinyang\HyperfAdmin\Annotaion;

use Attribute;
use Hyperf\Di\Annotation\AbstractAnnotation;

#[Attribute(Attribute::TARGET_METHOD)]
class PermissionCheck extends AbstractAnnotation
{
    public function __construct(public string $slug)
    {
        parent::__construct($slug);

    }
}