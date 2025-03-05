<div>
    @foreach($thread->communications()->orderBy('sent_at', 'desc')->get() as $communication)
        <div class="bg-base-100 rounded-lg p-4 mb-4 border border-base-300">
            <div class="flex justify-between items-start">
                <div>
                    <!-- Existing communication details -->
                </div>

                <div class="flex items-center space-x-2">
                    <!-- Add the delete button here -->
                    <livewire:correspondence.delete-communication
                        :communication="$communication"
                        :key="'delete-'.$communication->id"
                    />
                </div>
            </div>

            <!-- Rest of your communication display code -->
        </div>
    @endforeach
</div>
