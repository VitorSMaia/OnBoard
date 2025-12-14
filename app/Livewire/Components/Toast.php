<?php

namespace App\Livewire\Components;

use Livewire\Component;

class Toast extends Component
{
    public $toasts = [];

    protected $listeners = ['showToast'];

    public function showToast($message, $type = 'info', $duration = 3000, $icon = null)
    {
        $id = uniqid();

        $this->toasts[] = [
            'id' => $id,
            'message' => $message,
            'type' => $type, // success, error, warning, info
            'duration' => $duration,
            'icon' => $icon,
        ];

        // Auto-remover após a duração
        // if ($duration > 0) {
        //     $this->dispatch('removeToast', $id);
        // }
    }

    public function removeToast($id)
    {
        $this->toasts = array_filter($this->toasts, fn($toast) => $toast['id'] !== $id);
    }
    
    public function render()
    {
        return view('livewire.components.toast');
    }
}
