<div class="space-y-6">
    @if($entry->description)
        <div>
            <h4 class="font-medium mb-2">{{ __('docket.entry.fields.description') }}</h4>
            <p class="text-base-content/70">{{ $entry->description }}</p>
        </div>
    @endif

    <div class="grid grid-cols-2 gap-6">
        @if($entry->filing_party)
            <div>
                <h4 class="font-medium mb-2">{{ __('docket.entry.fields.filing_party') }}</h4>
                <p class="text-base-content/70">{{ $entry->filing_party }}</p>
            </div>
        @endif

        @if($entry->judge)
            <div>
                <h4 class="font-medium mb-2">{{ __('docket.entry.fields.judge') }}</h4>
                <p class="text-base-content/70">{{ $entry->judge }}</p>
            </div>
        @endif
    </div>

    @if($relatedDocuments->isNotEmpty())
        <div>
            <h4 class="font-medium mb-2">{{ __('docket.entry.related_documents') }}</h4>
            <ul class="space-y-2">
                @foreach($relatedDocuments as $document)
                    <li class="flex items-center gap-2">
                        <x-icon name="document" class="w-5 h-5 text-base-content/50" />
                        <a 
                            href="{{ route('documents.show', $document) }}"
                            class="link link-hover"
                        >
                            {{ $document->title ?: $document->original_filename }}
                        </a>
                        @if($document->exhibits_count)
                            <span class="badge badge-sm">
                                {{ trans_choice('docket.entry.exhibits_count', $document->exhibits_count) }}
                            </span>
                        @endif
                    </li>
                @endforeach
            </ul>
        </div>
    @endif

    @if($relatedCommunications->isNotEmpty())
        <div>
            <h4 class="font-medium mb-2">{{ __('docket.entry.related_communications') }}</h4>
            <ul class="space-y-2">
                @foreach($relatedCommunications as $communication)
                    <li class="flex items-center gap-2">
                        <x-icon 
                            name="{{ $communication->type }}" 
                            class="w-5 h-5 text-base-content/50" 
                        />
                        <a 
                            href="{{ route('communications.show', $communication) }}"
                            class="link link-hover"
                        >
                            {{ $communication->subject ?: __('docket.entry.communication_without_subject') }}
                        </a>
                        <span class="text-sm text-base-content/50">
                            {{ $communication->sent_at->format('M d, Y') }}
                        </span>
                    </li>
                @endforeach
            </ul>
        </div>
    @endif
</div>