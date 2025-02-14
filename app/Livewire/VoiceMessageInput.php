<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;

class VoiceMessageInput extends Component
{
    use WithFileUploads;

    public $message = '';
    public $height = '150px';
    public $name;
    public $isRecording = false;

    public function mount($name, $height = null)
    {
        $this->name = $name;
        if ($height) {
            $this->height = $height;
        }
    }

    public function appendTranscription($transcription): void
    {
        $this->message = trim($this->message . "\n" . $transcription);
    }

    public function render()
    {
        return view('livewire.voice-message-input');
    }
}
