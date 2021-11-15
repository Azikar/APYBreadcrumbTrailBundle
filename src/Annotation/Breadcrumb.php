<?php

/*
 * This file is part of the APYBreadcrumbTrailBundle.
 *
 * (c) Abhoryo <abhoryo@free.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace APY\BreadcrumbTrailBundle\Annotation;

/**
 * @Annotation
 */
#[\Attribute(\Attribute::IS_REPEATABLE | \Attribute::TARGET_CLASS | \Attribute::TARGET_METHOD)]
class Breadcrumb
{
    /**
     * @var string Title of the breadcrumb
     */
    private $title = null;

    /**
     * @var string The name of the route
     */
    private $routeName = null;

    /**
     * @var mixed An array of parameters for the route
     */
    private $routeParameters = array();

    /**
     * @var Boolean Whether to generate an absolute URL
     */
    private $routeAbsolute = false;

    /**
     * @var integer Position of the breadcrumb (default = 0)
     */
    private $position = 0;

    /**
     * @var string Template of the breadcrumb trail
     */
    private $template = null;

    /**
     * @var mixed An array of additional attributes for the breadcrumb
     */
    private $attributes = array();

    /**
     * @param array|string $title title, or the legacy array that contains all annotation data
     * @param ?array<string, string|array> $route
     * @param ?string $routeName
     * @param ?array<string,mixed> $routeParameters
     * @param bool $routeAbsolute
     * @param int $position
     * @param ?string $template
     * @param array $attributes
     */
    public function __construct(
        $title,
        $routeName = null,
        $routeParameters = null,
        $routeAbsolute = null,
        $position = null,
        $template = null,
        $attributes = null
    )
    {
        $data = [];

        if (is_string($title)) {
            $data = ["title" => $title];
        } elseif (is_array($title)) {
            $data = $title;
        }

        $data['routeName'] = $data['routeName'] ?? $routeName;
        $data['routeParameters'] = $data['routeParameters'] ?? $routeParameters;
        $data['routeAbsolute'] = $data['routeAbsolute'] ?? $routeAbsolute;
        $data['position'] = $data['position'] ?? $position;
        $data['template'] = $data['template'] ?? $template;
        $data['attributes'] = $data['attributes'] ?? $attributes;

        if (isset($data['value'])) {
            $data['title'] = $data['value'];
            unset($data['value']);
        }

        if (isset($data['route'])) {
            if (is_array($data['route'])) {
                foreach ($data['route'] as $key => $value) {
                    $method = 'setRoute'.$key;
                    if (!method_exists($this, $method)) {
                        throw new \BadMethodCallException(sprintf("Unknown property '%s' for the 'route' parameter on annotation '%s'.", $key, get_class($this)));
                    }
                    $this->$method($value);
                }
            }
            else {
                $data['routeName'] = $data['route'];
            }

            unset($data['route']);
        }

        foreach ($data as $key => $value) {

            // Do not attempt setting values that were provided as null
            if ($value === null) {
                continue;
            }

            $method = 'set'.$key;
            if (!method_exists($this, $method)) {
                throw new \BadMethodCallException(sprintf("Unknown property '%s' on annotation '%s'.", $key, get_class($this)));
            }
            $this->$method($value);
        }
    }

    /**
     * Sets the title of the breadcrumb.
     *
     * @param string $methods The title of the breadcrumb
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Sets the name of the route
     *
     * @param string $routeName The name of the route
     */
    public function setRouteName($routeName)
    {
        $this->routeName = $routeName;
    }

    public function getRouteName()
    {
        return $this->routeName;
    }

    /**
     * Sets an array of parameters for the route
     *
     * @param mixed $routeParameters An array of parameters for the route
     */
    public function setRouteParameters($routeParameters)
    {
        $this->routeParameters = $routeParameters;
    }

    public function getRouteParameters()
    {
        return $this->routeParameters;
    }

    /**
     * Whether to generate an absolute URL
     *
     * @param Boolean $routeName Whether to generate an absolute URL
     */
    public function setRouteAbsolute($routeAbsolute)
    {
        $this->routeAbsolute = $routeAbsolute;
    }

    public function getRouteAbsolute()
    {
        return $this->routeAbsolute;
    }

    /**
     * Sets the position of the breadcrumb.
     *
     * @param integer Position of the breadcrumb (default = 0)
     */
    public function setPosition($position)
    {
        $this->position = $position;
    }

    public function getPosition()
    {
        return $this->position;
    }

    /**
     * Sets the template of the breadcrumb trail.
     *
     * @param string Template of the breadcrumb trail.
     */
    public function setTemplate($template)
    {
        $this->template = $template;
    }

    public function getTemplate()
    {
        return $this->template;
    }

    /**
     * Sets the additional attributes for the breadcrumb.
     *
     * @param mixed Additional attributes for the breadcrumb.
     */
    public function setAttributes($attributes)
    {
        $this->attributes = $attributes;
    }

    public function getAttributes()
    {
        return $this->attributes;
    }
}
