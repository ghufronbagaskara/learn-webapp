@extends('layouts.app')

@section('content')
<div class="flex flex-col h-[calc(100vh-12rem)] md:flex-row gap-6">
    <!-- Sidebar (User Progress Summary) -->
    <div class="hidden md:flex flex-col w-72 bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-4 border-b border-gray-100 bg-gray-50">
            <h2 class="font-bold text-gray-900">Learning Progress</h2>
        </div>
        <div class="flex-1 overflow-y-auto p-4 space-y-6">
            @foreach($userCourses as $uc)
            <div>
                <div class="flex justify-between items-center mb-1">
                    <span class="text-xs font-semibold text-gray-700 truncate w-32">{{ $uc->course->title }}</span>
                    <span class="text-xs font-bold text-indigo-600">{{ number_format($uc->overall_progress, 0) }}%</span>
                </div>
                <div class="w-full bg-gray-100 rounded-full h-1.5">
                    <div class="bg-indigo-600 h-1.5 rounded-full transition-all duration-500" style="width: {{ $uc->overall_progress }}%"></div>
                </div>
            </div>
            @endforeach
        </div>
        <div class="p-4 border-t border-gray-100">
            <form action="{{ route('chat.clear') }}" method="POST">
                @csrf
                <button type="submit" class="w-full py-2 px-4 bg-red-50 text-red-600 rounded-lg text-sm font-semibold hover:bg-red-100 transition-colors">
                    <i class="fas fa-trash-alt mr-2"></i> Clear History
                </button>
            </form>
        </div>
    </div>

    <!-- Main Chat Area -->
    <div class="flex-1 flex flex-col bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <!-- Chat Header -->
        <div class="p-4 border-b border-gray-100 bg-white flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-indigo-100 rounded-full flex items-center justify-center text-indigo-600">
                    <i class="fas fa-robot text-xl"></i>
                </div>
                <div>
                    <h2 class="font-bold text-gray-900">MaxBot</h2>
                    <div class="flex items-center space-x-1">
                        <span class="w-2 h-2 bg-green-500 rounded-full"></span>
                        <span class="text-xs text-gray-500">AI Study Assistant</span>
                    </div>
                </div>
            </div>
            <!-- Mobile Clear History -->
            <form action="{{ route('chat.clear') }}" method="POST" class="md:hidden">
                @csrf
                <button type="submit" class="p-2 text-red-500 hover:bg-red-50 rounded-lg">
                    <i class="fas fa-trash-alt"></i>
                </button>
            </form>
        </div>

        <!-- Messages Area -->
        <div id="chat-messages" class="flex-1 overflow-y-auto p-4 space-y-4 bg-gray-50/50">
            <!-- Welcome Message -->
            <div class="flex items-start max-w-[85%] space-x-3">
                <div class="w-8 h-8 flex-shrink-0 bg-indigo-100 rounded-full flex items-center justify-center text-indigo-600">
                    <i class="fas fa-robot text-sm"></i>
                </div>
                <div class="bg-white p-4 rounded-2xl rounded-tl-none shadow-sm border border-gray-100">
                    <p class="text-sm text-gray-800">
                        Hello {{ $user->name }}! I'm MaxBot, your AI Study Assistant. I've analyzed your progress across {{ $userCourses->count() }} courses. How can I help you today?
                    </p>
                </div>
            </div>

            @foreach($history as $chat)
            <!-- User Message -->
            <div class="flex items-start flex-row-reverse max-w-[85%] ml-auto space-x-3 space-x-reverse">
                <div class="w-8 h-8 flex-shrink-0 bg-indigo-600 rounded-full flex items-center justify-center text-white font-bold text-xs">
                    {{ substr($user->name, 0, 1) }}
                </div>
                <div class="bg-indigo-600 p-4 rounded-2xl rounded-tr-none shadow-sm text-white">
                    <p class="text-sm">{{ $chat['user'] }}</p>
                    <span class="text-[10px] opacity-70 mt-1 block text-right">{{ \Carbon\Carbon::parse($chat['timestamp'])->format('H:i') }}</span>
                </div>
            </div>

            <!-- Bot Message -->
            <div class="flex items-start max-w-[85%] space-x-3">
                <div class="w-8 h-8 flex-shrink-0 bg-indigo-100 rounded-full flex items-center justify-center text-indigo-600">
                    <i class="fas fa-robot text-sm"></i>
                </div>
                <div class="bg-white p-4 rounded-2xl rounded-tl-none shadow-sm border border-gray-100">
                    <div class="text-sm text-gray-800 whitespace-pre-wrap">{!! nl2br(e($chat['bot'])) !!}</div>
                    <span class="text-[10px] text-gray-400 mt-1 block">{{ \Carbon\Carbon::parse($chat['timestamp'])->format('H:i') }}</span>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Input Area -->
        <div class="p-4 border-t border-gray-100 bg-white">
            <form id="chat-form" class="flex space-x-4">
                <div class="flex-1 relative">
                    <textarea id="user-message" rows="1" placeholder="Type your question here..." 
                        class="w-full py-3 px-4 bg-gray-100 border-transparent rounded-xl focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all resize-none overflow-hidden"></textarea>
                </div>
                <button type="submit" id="send-button" class="bg-indigo-600 text-white w-12 h-12 rounded-xl flex items-center justify-center hover:bg-indigo-700 transition-colors disabled:opacity-50">
                    <i class="fas fa-paper-plane"></i>
                </button>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    const chatMessages = document.getElementById('chat-messages');
    const chatForm = document.getElementById('chat-form');
    const userMessageInput = document.getElementById('user-message');
    const sendButton = document.getElementById('send-button');

    // Scroll to bottom on load
    chatMessages.scrollTop = chatMessages.scrollHeight;

    // Auto-resize textarea
    userMessageInput.addEventListener('input', function() {
        this.style.height = 'auto';
        this.style.height = (this.scrollHeight) + 'px';
    });

    // Handle Enter to send (Shift+Enter for newline)
    userMessageInput.addEventListener('keydown', function(e) {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            chatForm.dispatchEvent(new Event('submit'));
        }
    });

    chatForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const message = userMessageInput.value.trim();
        if (!message) return;

        // Add user message to UI
        addUserMessage(message);
        userMessageInput.value = '';
        userMessageInput.style.height = 'auto';
        
        // Show typing indicator
        const typingIndicator = addTypingIndicator();
        chatMessages.scrollTop = chatMessages.scrollHeight;
        
        sendButton.disabled = true;

        try {
            const response = await fetch('{{ route("chat.send") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ message: message })
            });

            const data = await response.json();
            
            // Remove typing indicator
            typingIndicator.remove();

            if (data.reply) {
                addBotMessage(data.reply);
            } else {
                addBotMessage('Sorry, I encountered an error: ' + (data.error || 'Unknown error'));
            }
        } catch (error) {
            typingIndicator.remove();
            addBotMessage('Sorry, something went wrong. Please try again later.');
            console.error(error);
        } finally {
            sendButton.disabled = false;
            chatMessages.scrollTop = chatMessages.scrollHeight;
        }
    });

    function addUserMessage(message) {
        const time = new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
        const html = `
            <div class="flex items-start flex-row-reverse max-w-[85%] ml-auto space-x-3 space-x-reverse">
                <div class="w-8 h-8 flex-shrink-0 bg-indigo-600 rounded-full flex items-center justify-center text-white font-bold text-xs">
                    {{ substr($user->name, 0, 1) }}
                </div>
                <div class="bg-indigo-600 p-4 rounded-2xl rounded-tr-none shadow-sm text-white">
                    <p class="text-sm">${escapeHtml(message)}</p>
                    <span class="text-[10px] opacity-70 mt-1 block text-right">${time}</span>
                </div>
            </div>
        `;
        chatMessages.insertAdjacentHTML('beforeend', html);
    }

    function addBotMessage(message) {
        const time = new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
        const html = `
            <div class="flex items-start max-w-[85%] space-x-3">
                <div class="w-8 h-8 flex-shrink-0 bg-indigo-100 rounded-full flex items-center justify-center text-indigo-600">
                    <i class="fas fa-robot text-sm"></i>
                </div>
                <div class="bg-white p-4 rounded-2xl rounded-tl-none shadow-sm border border-gray-100">
                    <div class="text-sm text-gray-800 whitespace-pre-wrap">${message.replace(/\n/g, '<br>')}</div>
                    <span class="text-[10px] text-gray-400 mt-1 block">${time}</span>
                </div>
            </div>
        `;
        chatMessages.insertAdjacentHTML('beforeend', html);
    }

    function addTypingIndicator() {
        const html = `
            <div id="typing-indicator" class="flex items-start max-w-[85%] space-x-3">
                <div class="w-8 h-8 flex-shrink-0 bg-indigo-100 rounded-full flex items-center justify-center text-indigo-600">
                    <i class="fas fa-robot text-sm"></i>
                </div>
                <div class="bg-white p-4 rounded-2xl rounded-tl-none shadow-sm border border-gray-100">
                    <div class="flex space-x-1">
                        <div class="w-2 h-2 bg-gray-300 rounded-full animate-bounce"></div>
                        <div class="w-2 h-2 bg-gray-300 rounded-full animate-bounce" style="animation-delay: 0.2s"></div>
                        <div class="w-2 h-2 bg-gray-300 rounded-full animate-bounce" style="animation-delay: 0.4s"></div>
                    </div>
                </div>
            </div>
        `;
        chatMessages.insertAdjacentHTML('beforeend', html);
        return document.getElementById('typing-indicator');
    }

    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
</script>
@endpush
@endsection
