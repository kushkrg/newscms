<?php

namespace App\Core;

class View
{
    private array $data = [];
    private string $layout = 'layouts/main';

    public function setLayout(string $layout): self
    {
        $this->layout = $layout;
        return $this;
    }

    public function assign(string|array $key, mixed $value = null): self
    {
        if (is_array($key)) {
            $this->data = array_merge($this->data, $key);
        } else {
            $this->data[$key] = $value;
        }
        return $this;
    }

    public function render(string $template, array $data = []): string
    {
        $data = array_merge($this->data, $data);
        $content = $this->renderPartial($template, $data);

        if ($this->layout) {
            $data['content'] = $content;
            return $this->renderPartial($this->layout, $data);
        }

        return $content;
    }

    public function renderPartial(string $template, array $data = []): string
    {
        $file = VIEW_PATH . '/' . $template . '.php';
        if (!file_exists($file)) {
            throw new \RuntimeException("View template not found: $file");
        }

        extract($data, EXTR_SKIP);
        ob_start();
        include $file;
        return ob_get_clean();
    }

    public static function partial(string $template, array $data = []): string
    {
        $view = new self();
        $view->setLayout('');
        return $view->renderPartial($template, $data);
    }
}
