<?php

namespace Core\Traits;

/**
 * Task 1:
 *
 * @var mixed
 * @author Fil Beluan
 */
trait RedirectTrait
{
    /**
     * Redirecto home page
     *
     * @return void
     */
    protected function redirect($to = '')
    {
        if (empty($to)) {
            return $this;
        }

        return self::to($to);
    }

    /**
     * Set session
     *
     * @param  array  $withs
     * @return null|object
     */
    protected function with(array $withs = [])
    {
        foreach ($withs as $key => $value) {
            $_SESSION['with'][$key] = $value;
        }

        return $this;
    }

    /**
     * Target location
     *
     * @param  string $to
     * @return null|array
     */
    protected function to($to = '/')
    {
        if ($this->app()->isTest()) {
            return $_SESSION;
        }

        ob_start();
        header('Location: ' . $to);
        ob_end_flush();
        exit;
    }
}
