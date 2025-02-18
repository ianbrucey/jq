<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;

/**
 * Purpose: The VoiceMessageInput is intended to be a portable component that allows a person to speak their thoughts and have them transcribed to be pasted in a textarea
 */
class VoiceMessageInput extends Component
{
    use WithFileUploads;

    public function updatedMessage($value)
    {
        $this->dispatch('voice-message-updated', name: $this->name, message: $value);
    }

    /**
     * The current message content in the textarea
     * @var string
     */
    public $message = '';

    /**
     * The height of the textarea element
     * @var string
     */
    public $height = '150px';

    /**
     * The name attribute for the textarea element
     * @var string
     */
    public $name;

    /**
     * Indicates whether audio recording is in progress
     * @var bool
     */
    public $isRecording = false;

    /**
     * Initialize the component with required parameters
     *
     * @param string $name The name attribute for the textarea
     * @param string|null $height Optional custom height for the textarea
     */
    public function mount($name, $height = null)
    {
        $this->name = $name;
        if ($height) {
            $this->height = $height;
        }
    }

    /**
     * Append transcribed text to the existing message
     *
     * @param string $transcription The transcribed text to append
     */
    public function appendTranscription($transcription): void
    {
        $this->message = trim($this->message . "\n" . $transcription);
        $this->dispatch('voice-message-updated', name: $this->name, message: $this->message);
    }

    /**
     * Render the component view
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('livewire.voice-message-input', [
            'attributes' => $this->attributes
        ]);
    }
}
